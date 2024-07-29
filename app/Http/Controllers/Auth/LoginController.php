<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Login endpoint
     * 
     * @bodyParam nip string required Example: 2121021210
     * @bodyParam password string required Example: password123
     */
    public function __invoke(Request $request)
    {
        $type = $request->input('by', 'nip');
        $validator = Validator::make($request->all(), [
            $type => 'required',
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

        $user = auth()->user();
        Log::info("logged in user: {$user}");
        $token = $user->createToken('accessToken')->plainTextToken;
        return response()->json([
            'message' => 'login success',
            'token' => $token,
            'user' => $user
        ]);
    }
}
