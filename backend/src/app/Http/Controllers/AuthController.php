<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     title="Travel Manager API",
 *     version="1.0.0",
 *     description="Gerenciamento de viagens corporativas",
 * )
 */

class AuthController extends Controller
{
    /**
     * @OA\PathItem(
     *     path="/login",
     *     @OA\Post(
     *         tags={"Authentication"},
     *         summary="Login",
     *         description="Realiza o login do usuário",
     *         @OA\RequestBody(
     *             required=true,
     *             @OA\JsonContent(
     *                 required={"email","password"},
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="password123")
     *             )
     *         ),
     *         @OA\Response(
     *             response=200,
     *             description="Successful login",
     *             @OA\JsonContent(
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *             )
     *         ),
     *         @OA\Response(
     *             response=401,
     *             description="Invalid credentials",
     *             @OA\JsonContent(
     *                 @OA\Property(property="error", type="string", example="Invalid email or password")
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     description="This endpoint allows a new user to register by providing necessary details.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid input data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User already exists")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while registering the user'], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'user_id' => Auth::user()->id,
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Token revoked']);
        }

        return response()->json(['message' => 'Token revoked']);
        // return response()->json(['error' => 'User not authenticated'], 401);
    }
}
