<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function register(Request $request)
    {
        try {
            $this->validate(
                $request,
                [
                    'name'     => 'required|min:4',
                    'email'    => 'required|email',
                    'password' => 'required|min:6|confirmed',
                ]
            );

            $userExists = !!User::whereEmail($request->email)->count();

            if ($userExists) {
                return response()->json(['error' => 'User already exists'], 403);
            }

            $user = User::create(
                [
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => bcrypt($request->password)
                ]
            );

            $token = $user->createToken('authToken')->accessToken;

            if ($token) {
                return response()->json(['token' => $token]);
            }

            return response()->json(['error' => 'Bad request'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function login(Request $request)
    {
        $data = [
            'email'    => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
