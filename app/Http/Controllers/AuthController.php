<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Authorization managment
 *
 * API's for login, register and logout in basic way
 */
class AuthController extends Controller
{
    /**
     * Register user
     *
     * This endpoint allows you to register a user in standard way using an email
     *
     * @bodyParam name string required User's name. Example: Adam
     * @bodyParam surname string required User's surname. Example: Kowalski
     * @bodyParam date_of_birth date required User's date of birth. Example: 2000-10-10
     * @bodyParam login string required User's login. Example: PrettyWoman
     * @bodyParam password string required User's Password - automatically encrypts with bcrypt. Example: password
     * @bodyParam password_confirmation string required Password confirmation of user's password. Example: password
     *
     * @response 201 {
     *   "message": "Pomyślnie zarejestrowano użytkownika",
     *   "data": {
     *         "id": 54
     *   }
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'login' => $request->input('login'),
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'date_of_birth' => $request->input('date_of_birth'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->assignRole('user');

        $data = [
            "message" => "Pomyślnie zarejestrowano użytkownika",
            "data" => ["id" => $user->id]
        ];

        return response()->json($data);
    }

    /**
     * Login user
     *
     * This endpoint allows you to login a user in a standard way using an email
     *
     * @bodyParam email string required User's Email. Example: definetly@not.admin
     * @bodyParam password string required User's Password - automatically encrypts with bcrypt. Example: password
     *
     * @response 201 {
     * "data": {
     * "user": {
     * "id": 54,
     * "name": "Angelika",
     * "surname": "Iskra",
     * "date_of_birth": "2000-10-27",
     * "login": "admin123456",
     * "deleted_at": null,
     * "created_at": "2021-12-20T13:38:00.000000Z",
     * "updated_at": "2021-12-20T13:38:00.000000Z",
     * "role": "user",
     * "roles": [
     * {
     * "id": 1,
     * "name": "user",
     * "guard_name": "web",
     * "created_at": "2021-12-20T13:08:58.000000Z",
     * "updated_at": "2021-12-20T13:08:58.000000Z",
     * "pivot": {
     * "model_id": 54,
     * "role_id": 1,
     * "model_type": "App\\Models\\User"
     * }
     * }
     * ]
     * },
     * "token": "6|sBlAGXIEO3cWkW4cxSUwOgNJzFTIJRAMnlJXTkmz"
     * }
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json([
                'message' => "Login lub hasło są niepoprawne"
            ], 401);
        }

        $user = auth()->user();
        $token = $user->createToken('API Token');
        $user["role"] = $user->roles[0]->name;

        $data = ["data" => [
            'user' => $user,
            'token' => $token->plainTextToken
        ]];

        return response()->json($data);
    }

    /**
     * Logouts user
     *
     * @authenticated
     *
     * This endpoint allows you to logout as current user.
     *
     * @response 201 {
     * "message": "Pomyślnie wylogowano"
     * }
     *
     */
    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        $data = [
            'message' => "Pomyślnie wylogowano"
        ];

        return response()->json($data);
    }
}
