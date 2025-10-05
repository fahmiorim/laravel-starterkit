<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $users = $this->userService->getAllUsers(
            $request->only(['search', 'role', 'is_active']),
            10
        );

        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = Role::all();

        return view('admin.users.create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id']
        ]);

        $userData = $request->only(['name', 'email', 'password', 'phone']);
        $userData['is_active'] = true;

        $user = $this->userService->createUser($userData);

        // Assign roles using service
        $this->userService->assignRoles($user->id, $request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $user = $this->userService->getUserWithRolesAndPermissions($id);

        if (!$user) {
            abort(404);
        }

        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            abort(404);
        }

        $roles = Role::all();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users')->ignore($id)
            ],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['required', 'boolean'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id']
        ]);

        $userData = $request->only(['name', 'email', 'phone', 'is_active']);

        // Add password if provided
        if ($request->filled('password')) {
            $userData['password'] = $request->password;
        }

        $success = $this->userService->updateUser($id, $userData);

        if (!$success) {
            return back()->with('error', 'Failed to update user.');
        }

        // Sync roles using service
        $this->userService->assignRoles($id, $request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        // Prevent toggling your own status
        if (Auth::id() == $id) {
            return back()->with('error', 'You cannot change your own status.');
        }

        $success = $this->userService->toggleUserStatus($id);

        if (!$success) {
            return back()->with('error', 'Failed to toggle user status.');
        }

        return back()->with('success', 'User status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        // Prevent deleting yourself
        if (Auth::id() == $id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $success = $this->userService->deleteUser($id);

        if (!$success) {
            return back()->with('error', 'Failed to delete user.');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
