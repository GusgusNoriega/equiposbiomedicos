<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-roles')->only('index');
        $this->middleware('permission:crear-roles')->only(['create', 'store']);
        $this->middleware('permission:editar-roles')->only(['edit', 'update']);
        $this->middleware('permission:eliminar-roles')->only('destroy');
    }

    public function index(): View
    {
        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->with('permissions:id,name,code,module')
            ->orderBy('name')
            ->paginate(9);

        $stats = [
            'roles' => Role::count(),
            'system_roles' => Role::where('is_system', true)->count(),
            'permissions' => Permission::count(),
        ];

        return view('admin.roles.index', compact('roles', 'stats'));
    }

    public function create(): View
    {
        return view('admin.roles.create', [
            'role' => new Role(),
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:roles,name'],
            'code' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        $code = $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (Role::where('code', $code)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otro rol.'])
                ->withInput();
        }

        $role = Role::create([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        $role->permissions()->sync($validated['permission_ids'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role): View
    {
        $role->load('permissions:id');

        return view('admin.roles.edit', [
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:roles,name,' . $role->id],
            'code' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        $code = $role->is_system
            ? $role->code
            : $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (Role::where('code', $code)->where('id', '!=', $role->id)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otro rol.'])
                ->withInput();
        }

        $role->update([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
        ]);

        $role->permissions()->sync($validated['permission_ids'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return back()->with('error', 'Los roles del sistema no se pueden eliminar.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'No puedes eliminar un rol que tiene usuarios asignados.');
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }

    private function permissionGroups(): Collection
    {
        return Permission::query()
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => $permission->module ?: 'General');
    }

    private function resolveCode(?string $code, string $name): string
    {
        return Str::slug(filled($code) ? $code : $name);
    }
}
