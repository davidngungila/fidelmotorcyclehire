<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    use FlashMessages;

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('q', '');

        $query = Permission::with('roles');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%");
            });
        }

        $permissions = $query->latest()->paginate($perPage);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed permissions list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $search,
                'per_page' => $perPage,
                'total_count' => $permissions->total(),
            ],
        ]);

        return view('admin.permissions.index', [
            'permissions' => $permissions,
            'searchQuery' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(Request $request)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed create permission form',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'module' => ['nullable', 'string', 'max:100'],
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
            'module' => $validated['module'] ?? 'general',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'permission',
            'subject_id' => (string) $permission->id,
            'description' => "Admin created permission: {$permission->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'permission_name' => $permission->name,
                'module' => $permission->module,
            ],
        ]);

        $this->success("Permission {$permission->display_name} created successfully.");

        return redirect()->route('admin.permissions.index');
    }

    public function edit(Request $request, int $id)
    {
        $permission = Permission::with('roles')->findOrFail($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'permission',
            'subject_id' => (string) $permission->id,
            'description' => "Admin viewed edit permission form: {$permission->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.permissions.edit', [
            'permission' => $permission,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$id],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'module' => ['nullable', 'string', 'max:100'],
        ]);

        $permission->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
            'module' => $validated['module'] ?? 'general',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'permission',
            'subject_id' => (string) $permission->id,
            'description' => "Admin updated permission: {$permission->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'permission_name' => $permission->name,
                'module' => $permission->module,
            ],
        ]);

        $this->success("Permission {$permission->display_name} updated successfully.");

        return redirect()->route('admin.permissions.index');
    }

    public function destroy(Request $request, int $id)
    {
        $permission = Permission::findOrFail($id);
        $permissionName = $permission->name;
        $permissionId = $permission->id;

        $permission->roles()->detach();
        $permission->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'permission',
            'subject_id' => (string) $permissionId,
            'description' => "Admin deleted permission: {$permissionName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'permission_name' => $permissionName,
            ],
        ]);

        $this->success("Permission {$permissionName} deleted successfully.");

        return redirect()->route('admin.permissions.index');
    }
}
