<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    use FlashMessages;

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('q', '');

        $query = Role::with('permissions');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->latest()->paginate($perPage);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed roles list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $search,
                'per_page' => $perPage,
                'total_count' => $roles->total(),
            ],
        ]);

        return view('admin.roles.index', [
            'roles' => $roles,
            'searchQuery' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(Request $request)
    {
        $permissions = Permission::all();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed create role form',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.roles.create', [
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (! empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'role',
            'subject_id' => (string) $role->id,
            'description' => "Admin created role: {$role->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'role_name' => $role->name,
                'permissions_count' => count($validated['permissions'] ?? []),
            ],
        ]);

        $this->success("Role {$role->display_name} created successfully.");

        return redirect()->route('admin.roles.index');
    }

    public function edit(Request $request, int $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'role',
            'subject_id' => (string) $role->id,
            'description' => "Admin viewed edit role form: {$role->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$id],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (! empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->detach();
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'role',
            'subject_id' => (string) $role->id,
            'description' => "Admin updated role: {$role->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'role_name' => $role->name,
                'permissions_count' => count($validated['permissions'] ?? []),
            ],
        ]);

        $this->success("Role {$role->display_name} updated successfully.");

        return redirect()->route('admin.roles.index');
    }

    public function destroy(Request $request, int $id)
    {
        $role = Role::findOrFail($id);
        $roleName = $role->name;
        $roleId = $role->id;

        if (in_array(strtolower($roleName), ['admin', 'member'])) {
            $this->error('Cannot delete default system roles.');

            return redirect()->route('admin.roles.index');
        }

        $role->users()->detach();
        $role->permissions()->detach();
        $role->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'role',
            'subject_id' => (string) $roleId,
            'description' => "Admin deleted role: {$roleName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'role_name' => $roleName,
            ],
        ]);

        $this->success("Role {$roleName} deleted successfully.");

        return redirect()->route('admin.roles.index');
    }
}
