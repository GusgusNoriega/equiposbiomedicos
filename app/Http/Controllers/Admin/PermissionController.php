<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-permisos')->only('index');
        $this->middleware('permission:crear-permisos')->only(['create', 'store']);
        $this->middleware('permission:editar-permisos')->only(['edit', 'update']);
        $this->middleware('permission:eliminar-permisos')->only('destroy');
    }

    public function index(): View
    {
        $permissions = Permission::query()
            ->withCount('roles')
            ->orderBy('module')
            ->orderBy('name')
            ->paginate(12);

        $stats = [
            'permissions' => Permission::count(),
            'modules' => Permission::distinct('module')->count('module'),
            'assigned' => Permission::has('roles')->count(),
        ];

        return view('admin.permissions.index', compact('permissions', 'stats'));
    }

    public function create(): View
    {
        return view('admin.permissions.create', [
            'permission' => new Permission(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:permissions,name'],
            'code' => ['nullable', 'string', 'max:120'],
            'module' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $code = $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (Permission::where('code', $code)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otro permiso.'])
                ->withInput();
        }

        Permission::create([
            'name' => $validated['name'],
            'code' => $code,
            'module' => filled($validated['module'] ?? null) ? Str::slug($validated['module']) : null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso creado correctamente.');
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:permissions,name,' . $permission->id],
            'code' => ['nullable', 'string', 'max:120'],
            'module' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $code = $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (Permission::where('code', $code)->where('id', '!=', $permission->id)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otro permiso.'])
                ->withInput();
        }

        $permission->update([
            'name' => $validated['name'],
            'code' => $code,
            'module' => filled($validated['module'] ?? null) ? Str::slug($validated['module']) : null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso actualizado correctamente.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        if ($permission->roles()->exists()) {
            return back()->with('error', 'No puedes eliminar un permiso que ya esta asignado a un rol.');
        }

        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso eliminado correctamente.');
    }

    private function resolveCode(?string $code, string $name): string
    {
        return Str::slug(filled($code) ? $code : $name);
    }
}
