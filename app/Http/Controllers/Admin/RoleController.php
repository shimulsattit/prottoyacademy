<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RoleService;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    // List all roles
    public function index(Request $request)
    {

        if (auth()->guard('admin')->user()->hasPermissionTo('roles.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            $roles = $this->roleService->getAllRoles();
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    return view('portal.role.action', compact('model'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('portal.role.index');
    }

    // Show create form
    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('roles.create') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $permissions = Permission::all();
        return view('portal.role.create', compact('permissions'));
    }

    // Store a new role
    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('roles.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->roleService->createRole($request);
    }

    // Show edit form
    public function edit($id)
    {

        if (auth()->guard('admin')->user()->hasPermissionTo('roles.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $role = $this->roleService->findRoleById($id);
        $role_permissions = [];
		foreach ($role->permissions as $role_perm) {
			$role_permissions[] = $role_perm->name;
		}

        $permissions = $this->roleService->getAllPermission();
        
        return view('portal.role.edit', compact('role', 'permissions', 'role_permissions'));
    }

    // Update an existing role
    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('roles.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->roleService->updateRole($id, $request);
    }

    // Delete a role
    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('roles.delete') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $this->roleService->deleteRole($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Role And Permission Delete"
        ]);
    }
}
