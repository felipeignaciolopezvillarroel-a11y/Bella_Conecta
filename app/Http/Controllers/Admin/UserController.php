<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
public function index(Request $request)
{
    $search = trim((string) $request->get('search', ''));

    $users = User::query()
        ->webUsers()
        ->when($search !== '', function ($q) use ($search) {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->withCount('bookings')
        ->orderByDesc('created_at')
        ->paginate(8)
        ->withQueryString();

    return view('admin.users.index', compact('users', 'search'));
}

    public function show(User $user)
    {
        abort_unless($user->system_role === 'usuario', 404);

        $user->loadCount('bookings');

        $bookings = $user->bookings()
            ->with(['service', 'employee'])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->limit(20)
            ->get();

        return view('admin.users.show', compact('user', 'bookings'));
    }

    public function destroy(User $user)
    {
        abort_unless($user->system_role === 'usuario', 403);

        // Cancela reservas activas antes de borrar (opcional pero recomendable)
        $user->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->update(['status' => 'cancelled']);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}