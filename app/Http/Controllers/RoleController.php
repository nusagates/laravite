<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nusagates\Helper\Responses;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        $role = Role::with(['permissions'])->where('name', '!=', 'Super Admin')->latest()->paginate(20);
        return Responses::showSuccessMessage('success', $role);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:3|max:20',
        ];
        $validation = Validator::make($request->post(), $rules);
        if ($validation->fails()) {
            return Responses::showValidationError($validation);
        }
        $role = Role::firstOrCreate(['name' => $request->name]);
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role = $role->syncPermissions($permissions);
        return Responses::showSuccessMessage('Role has been created', $role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, Role $role)
    {

        $permissions = Permission::whereIn('id', $request->permissions)->get();
        if ($role->name !== $request->name) {
            $role->update(['name' => $request->name]);
        }
        $role = $role->syncPermissions($permissions);

        //update user permissions besides on their roles
        $users = User::role($role)->get();
        foreach ($users as $user) {
            foreach ($user->roles as $userRole) {
                $user->syncPermissions($userRole->permissions);
            }
        }
        return Responses::showSuccessMessage('Role has been updated', $role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return Responses::showSuccessMessage('Role has ben deleted');
    }
}
