<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Login endpoint
     * 
     * @bodyParam username string required Example: admin
     * @bodyParam password string required Example: password123
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:4',
            'password' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors() 
            ], 412);
        }

        $login = auth()->attempt($validator->validated());
        if (!$login) {
            return response()->json([
                'message' => 'wrong username or password'
            ], 401);
        }

        $token = auth()->user()->createToken('accessToken')->plainTextToken;
        return response()->json([
            'message' => 'login success',
            'token' => $token
        ]);
    }
}
