<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nusagates\Helper\Responses;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(Request $request)
    {
        $users = User::with('roles.permissions')->latest()->paginate(20);
        return Responses::showSuccessMessage('success', $users);
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string',
        ];
        $validation = Validator::make($request->post(), $rules);
        if ($validation->fails()) {
            return Responses::showValidationError($validation);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $roles = Role::whereIn('id', $request->roles)->get();
        $user = $user->syncRoles($roles);
        foreach ($roles as $role) {
            $user->syncPermissions($role->permissions);
        }
        return Responses::showSuccessMessage('An User has been created', $user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|min:3|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|confirmed|min:8',
            'password_confirmation' => 'nullable|string',
        ];
        $validation = Validator::make($request->post(), $rules);
        if ($validation->fails()) {
            return Responses::showValidationError($validation);
        }
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if ($request->password !== null) {
            $userData['password'] = bcrypt($request->password);
        }
        $user->update($userData);
        $roles = Role::with('permissions')->whereIn('id', $request->roles)->get();
        $user = $user->syncRoles($roles);
        foreach ($roles as $role) {
            $user->syncPermissions($role->permissions);
        }
        return Responses::showSuccessMessage('User has been updated', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function destroy(User $user)
    {
        $user->delete();
        return Responses::showSuccessMessage('User has been deleted');
    }
}
