<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Nusagates\Helper\Responses;

class AuthController extends Controller
{
    /**
     * Handle login request by client app
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validation = Validator::make($request->post(), $rules);
        if ($validation->fails()) {
            return Responses::showValidationError($validation);
        }
        //check user using email
        $user = User::where('email', $request->email)->first();
        //show message whenever user is not found
        if (!$user) return Responses::showErrorMessage('User not found');
        //show message whenever password entered is not valid
        if (!password_verify($request->password, $user->password)) return Responses::showErrorMessage('Password is not valid');

        //show message whenever user & password is validated
        $user['token'] = $user->createToken("API TOKEN")->plainTextToken;
        return Responses::showSuccessMessage('Authenticated', $user);
    }

    /**
     * handing registration request by client app
     * @param Request $request
     * @return array
     */
    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|min:3|max:25',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];
        $validation = Validator::make($request->post(), $rules);
        if ($validation->fails()) {
            return Responses::showValidationError($validation);
        }
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);
        return Responses::showSuccessMessage("Your account has been created", $user);
    }

    /**
     * handling logout request from client app
     * @return array
     */
    public function logout()
    {
        $token = Auth::user()->currentAccessToken()->delete();
        return Responses::showSuccessMessage('Successfully logged out of the app', $token);
    }

    /**
     * update user account
     * @param Request $request
     * @param User $user
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
        if ($request->avatar !== null) {
            $path = public_path('upload/avatar/');
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $img = Image::make($request->avatar);
            $img->save($path . $user->id . '.png');
        }
        $user['token'] = $request->bearerToken();
        return Responses::showSuccessMessage('Account has been updated', $user);
    }

    /**
     * update roles and permissions
     * @return array
     */
    public function auth(Request $request)
    {
        $user = Auth::user();
        $user['token'] = $request->bearerToken();
        return Responses::showSuccessMessage('Authenticated', $user);
    }
}
