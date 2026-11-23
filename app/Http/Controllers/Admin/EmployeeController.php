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
        // Cargamos la relación con el usuario para poder buscar por el rol de sistema
        $query = Employee::with('user');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // Buscamos en el nombre del empleado
                $q->where('name', 'like', "%{$search}%");

                // Buscamos en la especialidad (columna 'role' de la tabla employees)
                $q->orWhere('role', 'like', "%{$search}%");

                // Buscamos en la categoría (profesional / administrativo)
                $q->orWhere('category', 'like', "%{$search}%");

                // Buscamos en el rol de sistema del usuario asociado (empleado / admin)
                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('system_role', 'like', "%{$search}%");
                });

                // Si buscan "Activo" o "Inactivo" (opcional, porque también está el estado)
                if (str_contains(strtolower($search), 'activ')) {
                    $q->orWhere('active', true);
                }
                if (str_contains(strtolower($search), 'inactiv')) {
                    $q->orWhere('active', false);
                }
            });
        }

        // Paginamos de 8 en 8 y mantenemos la búsqueda en la URL
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

        // Crear usuario
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'system_role' => $data['system_role'],
        ]);

        $data['user_id'] = $user->id;

        if ($request->filled('photo_file')) {
            $data['photo'] = $this->storeWebpFromBase64($request->input('photo_file'));
        } elseif ($request->hasFile('photo_file')) {
            $data['photo'] = $this->storeWebp($request->file('photo_file'));
        }

        $data['active'] = $request->has('active');

        // 1. Crear el empleado
        $employee = Employee::create($data);

        // 2. Sincronizar los servicios seleccionados
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

        // Actualizar usuario
        $userData = [
            'name'  => $data['name'],
            'email' => $data['email'],
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

        if ($request->filled('photo_file')) {
            if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $this->storeWebpFromBase64($request->input('photo_file'));
        } elseif ($request->hasFile('photo_file')) {
            if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $this->storeWebp($request->file('photo_file'));
        }

        $data['active'] = $request->has('active');

        // 1. Actualizar el empleado
        $employee->update($data);

        // 2. Sincronizar los servicios seleccionados
        $employee->services()->sync($request->input('services', []));

        return redirect()->route('admin.employees.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }

        // Eliminar usuario asociado si existe
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
            'role'        => 'nullable|string|max:255', // especialidad
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

        if (!Storage::disk('public')->exists('employees')) {
            Storage::disk('public')->makeDirectory('employees');
        }

        $manager = new ImageManager(new Driver());
        $encoded = $manager->read($file->getRealPath())
            ->cover(600, 600)
            ->toWebp(80);

        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    private function storeWebpFromBase64(string $dataUrl): string
    {
        $filename = Str::uuid() . '.webp';
        $path = 'employees/' . $filename;

        if (!Storage::disk('public')->exists('employees')) {
            Storage::disk('public')->makeDirectory('employees');
        }

        $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $dataUrl);
        $binary = base64_decode($base64);

        $manager = new ImageManager(new Driver());
        $encoded = $manager->read($binary)
            ->cover(600, 600)
            ->toWebp(80);

        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }
}
