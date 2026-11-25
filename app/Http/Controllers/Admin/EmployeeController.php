<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('user');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
                $q->orWhere('role', 'like', "%{$search}%");
                $q->orWhere('category', 'like', "%{$search}%");
                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('system_role', 'like', "%{$search}%");
                });

                if (str_contains(strtolower($search), 'activ')) {
                    $q->orWhere('active', true);
                }
                if (str_contains(strtolower($search), 'inactiv')) {
                    $q->orWhere('active', false);
                }
            });
        }

        $employees = $query->orderBy('name')->paginate(8)->withQueryString();

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateEmployee($request);

        $user = User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'system_role' => $data['system_role'],
        ]);

        $data['user_id'] = $user->id;

        if ($request->filled('photo_file')) {
            $data['photo'] = $this->storeWebpFromBase64($request->input('photo_file'));
        } elseif ($request->hasFile('photo_file')) {
            $data['photo'] = $this->storeWebp($request->file('photo_file'));
        }

        $data['active'] = $request->has('active');

        $employee = Employee::create($data);
        $employee->services()->sync($request->input('services', []));

        return redirect()->route('admin.employees.index')
            ->with('success', 'Empleado creado correctamente.');
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $this->validateEmployee($request, $employee);

        $userData = [
            'name'        => $data['name'],
            'email'       => $data['email'],
            'system_role' => $data['system_role'],
        ];
        
        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        if ($employee->user) {
            $employee->user->update($userData);
        } else {
            $userData['password'] = Hash::make($data['password'] ?? Str::random(16));
            $userData['role'] = 'employee';
            $user = User::create($userData);
            $data['user_id'] = $user->id;
        }

        // Usamos el disco por defecto (que será S3 en Render)
        $disk = config('filesystems.default');

        if ($request->filled('photo_file')) {
            if ($employee->photo && Storage::disk($disk)->exists($employee->photo)) {
                Storage::disk($disk)->delete($employee->photo);
            }
            $data['photo'] = $this->storeWebpFromBase64($request->input('photo_file'));
        } elseif ($request->hasFile('photo_file')) {
            if ($employee->photo && Storage::disk($disk)->exists($employee->photo)) {
                Storage::disk($disk)->delete($employee->photo);
            }
            $data['photo'] = $this->storeWebp($request->file('photo_file'));
        }

        $data['active'] = $request->has('active');

        $employee->update($data);
        $employee->services()->sync($request->input('services', []));

        return redirect()->route('admin.employees.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Employee $employee)
    {
        $disk = config('filesystems.default');

        if ($employee->photo && Storage::disk($disk)->exists($employee->photo)) {
            Storage::disk($disk)->delete($employee->photo);
        }

        if ($employee->user) {
            $employee->user->delete();
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }

    private function validateEmployee(Request $request, ?Employee $employee = null): array
    {
        $userId = $employee && $employee->user ? $employee->user->id : null;

        return $request->validate([
            'name'        => 'required|string|max:255',
            'role'        => 'nullable|string|max:255',
            'category'    => 'required|in:profesional,administrativo',
            'bio'         => 'nullable|string|max:1000',
            'email'       => 'required|email|max:255|unique:users,email' . ($userId ? ',' . $userId : ''),
            'password'    => [$userId ? 'nullable' : 'required', 'string', Password::min(8)],
            'system_role' => 'required|in:usuario,empleado,admin',
            'photo_file'  => 'nullable|string',
        ], [
            'name.required'        => 'El nombre es obligatorio.',
            'category.required'    => 'Debes elegir una categoría.',
            'email.required'       => 'El correo es obligatorio.',
            'email.unique'         => 'Este correo ya está registrado.',
            'password.required'    => 'La contraseña es obligatoria.',
            'password.min'         => 'La contraseña debe tener al menos 8 caracteres.',
            'system_role.required' => 'Debes elegir un rol de sistema.',
            'system_role.in'       => 'El rol debe ser "usuario", "empleado" o "admin".',
        ]);
    }

    private function storeWebp($file): string
    {
        $filename = Str::uuid() . '.webp';
        $path = 'employees/' . $filename;

        $manager = new ImageManager(new Driver());
        $encoded = $manager->read($file->getRealPath())
            ->cover(600, 600)
            ->toWebp(80);

        // Usamos el disco por defecto
        $disk = config('filesystems.default');
        Storage::disk($disk)->put($path, (string) $encoded);

        return $path;
    }

    private function storeWebpFromBase64(string $dataUrl): string
    {
        $filename = Str::uuid() . '.webp';
        $path = 'employees/' . $filename;

        $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $dataUrl);
        $binary = base64_decode($base64);

        $manager = new ImageManager(new Driver());
        $encoded = $manager->read($binary)
            ->cover(600, 600)
            ->toWebp(80);

        // Usamos el disco por defecto
        $disk = config('filesystems.default');
        Storage::disk($disk)->put($path, (string) $encoded);

        return $path;
    }
}