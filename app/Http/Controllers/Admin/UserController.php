<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use FlashMessages;

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('q', '');

        $query = User::with('roles');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('member_number', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate($perPage);

        if ($users instanceof LengthAwarePaginator) {
            $users->appends([
                'q' => $search,
                'per_page' => $perPage,
            ]);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed users list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $search,
                'per_page' => $perPage,
                'total_count' => $users->total(),
            ],
        ]);

        return view('admin.users.index', [
            'users' => $users,
            'searchQuery' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(Request $request)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed create user form',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $roles = Role::all();

        return view('admin.users.create', [
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'member_number' => $validated['member_number'] ?? null,
            'status' => $request->input('status', 'active'),
        ]);

        if (! empty($validated['role'])) {
            try {
                $user->assignRole($validated['role']);
            } catch (\Throwable $e) {
                $user->role = $validated['role'];
                $user->save();
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'description' => "Admin created user: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'user_email' => $user->email,
                'user_role' => $validated['role'],
                'member_number' => $validated['member_number'] ?? null,
            ],
        ]);

        $this->success("User {$user->name} created successfully.");

        return redirect()->route('admin.users.index');
    }

    public function edit(Request $request, int $id)
    {
        $user = User::with('roles')->findOrFail($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'description' => "Admin viewed edit user form: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $roles = Role::all();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $request->input('status', $user->status ?? 'active'),
        ];

        if (! empty($validated['member_number'])) {
            $updateData['member_number'] = $validated['member_number'];
        }

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if (! empty($validated['role'])) {
            try {
                $roleModel = Role::where('name', $validated['role'])->first();
                if ($roleModel) {
                    $user->roles()->sync([$roleModel->id]);
                }
            } catch (\Throwable $e) {
                $user->role = $validated['role'];
                $user->save();
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'description' => "Admin updated user: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'user_email' => $user->email,
                'updated_fields' => array_keys($updateData),
            ],
        ]);

        $this->success("User {$user->name} updated successfully.");

        return redirect()->route('admin.users.index');
    }

    public function destroy(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $userId = $user->id;

        try {
            $user->roles()->detach();
        } catch (\Throwable $e) {
        }

        if (method_exists($user, 'forceDelete')) {
            try {
                $user->forceDelete();
            } catch (\Throwable $e) {
                $user->delete();
            }
        } else {
            $user->delete();
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $userId,
            'description' => "Admin deleted user: {$userName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'user_name' => $userName,
                'deleted_by' => Auth::id(),
            ],
        ]);

        $this->success("User {$userName} deleted successfully.");

        return redirect()->route('admin.users.index');
    }
}
