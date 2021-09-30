<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

        if($user = Auth::attempt([
            'username' => $request->input('username'),
            'password' => $request->input('password')]))
        {
            $user = $request->user();
            $tokenResult = $user->createToken('Api Token');
            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'state' => true,
                'message' => 'you are logged in.',
                'data' => [
                    'token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'username' => $user->username,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                ],
            ], 200);
        }

        return response()->json([
            'state' => true,
            'message' => 'user is not found',
            'data' => null
        ], 404);
    }

    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->mobile = $request->input('mobile');
        $user->home_phone = $request->input('home_phone');
        $user->work_phone = $request->input('work_phone');
        $user->home_address = $request->input('home_address');
        $user->work_address = $request->input('work_address');
        $user->image = $request->input('image');
        if($user->save())
            return response()->json([
                'state' => true,
                'message' => 'you are successfully registered',
                'data' => null,
            ], 200);
    }
}
