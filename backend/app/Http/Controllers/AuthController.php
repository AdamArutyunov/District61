<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        //validate incoming request
        $this->validate($request, [
            'login' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        try {

            $user = new User();
            $user->fill($request->all());
            $user->password = app('hash')->make($request->get("password"));
            $user->save();

            //return successful response
            return response()->json(["error" => false, "token" => Auth::attempt($request->only(['email', 'password'])), 'expires_in' => Auth::factory()->getTTL() * 60], 201);

        } catch (Exception $e) {
            //return error message
            return response()->json(['error' => true, "message" => $e->getMessage()], 409);
        }

    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

}
