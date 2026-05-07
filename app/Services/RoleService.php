<?php 

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\AdminRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleService {
    // protected $adminRepository;

    // public function __construct(AdminRepositoryInterface $adminRepository)
    // {
    //     $this->adminRepository = $adminRepository;
    // }

    public function getAllRoles()
    {
        return Role::all();
    }

    public function findRoleById($id)
    {
        return Role::findOrFail($id);
    }

    public function getAllPermission()
    {
        return Permission::all();
    }

    public function createRole($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $role = Role::create(['name' => $request->name, 'guard_name' => 'admin']);
        $role->givePermissionTo($request->permissions);

        return response()->json([
            'status' => true,
            'message' => 'Role Created Successfully',
            'goto' => route('portal.roles.index')
        ]);
    }

    public function updateRole($id, $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4|unique:roles,name,'. $id,
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

		$role->syncPermissions($request->permissions);

        return response()->json([
            'status' => true,
            'message' => 'Role Updated Successfully',
            'goto' => route('portal.roles.index')
        ]);
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        return $role->delete();
    }

}