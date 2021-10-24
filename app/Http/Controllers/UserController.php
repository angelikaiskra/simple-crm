<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AccessLevelRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * @group User Managment
 *
 * @authenticated
 *
 * API's for user resource managment
 *
 */
class UserController extends Controller
{
    /**
     * Returns a list of users
     *
     * Access Level needed: 2<br>
     *
     * Display a paginated(by 20) listing of the users.<br>
     *
     * @queryParam page integer Non required field - self explanatory
     * @queryParam limit integer Non required field - self explanatory
     *
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 20);

        return UserResource::collection(User::paginate($limit));
    }

    /**
     * Return single user
     *
     * Access Level needed: 2<br>
     *
     * Display the specified user by id.<br>
     *
     * @urlParam user integer required Id of user in database
     *
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update single user
     *
     * Access Level needed: 2<br>
     *
     * Updates user's columns find by id
     *
     * @urlParam user integer required Id of user in database
     *
     * @bodyParam name string optional User's name. Example: Adam
     * @bodyParam surname string optional User's surname. Example: Kowalski
     * @bodyParam date_of_birth date optional User's date of birth. Example: 2000-10-10
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return response()->json([
            'message' => "Pomyślnie zaktualizowano użytkownika",
            'data' => $user->fresh()
        ]);
    }

    /**
     * Delete single user
     *
     * Access Level needed: 2<br>
     *
     * Remove the specified user from database.
     * May throw ModelNotFoundException if user's is not in database
     *
     * @urlParam user integer required Id of user in database
     *
     */
    public function destroy(User $user): JsonResponse
    {
        try{
            $user->delete();
        } catch (ModelNotFoundException $exception){
            info($exception);
        }
        return response()->json([
            "message" => "Pomyślnie usunięto użytkownika"
        ]);
    }

    /**
     * Current user
     *
     * Display currently logged in user.<br>
     *
     */
    public function me()
    {
        return $this->show(auth()->user());
    }

//    /**
//     * Self update
//     *
//     * Updates first and last names of user found by id
//     *
//     * @bodyParam first_name string optional User's first_name. Example: NotAdminName
//     * @bodyParam last_name string optional User's last_name. Example: NotAdminLastName
//     * @bodyParam newsletter boolean optional User's agreement for newsletter. Example: 1
//     *
//     * @responseFile storage/responses/users/update.200.json
//     * @responseFile status=401 scenario="User has not valid token" storage/responses/generic/unauthenticated.401.json
//     */
//    public function selfUpdate(SelfUpdateUserRequest $request) {
//        $user = auth()->user();
//        $user->update($request->validated());
//
//        return response()->json([
//            'message' => "Pomyślnie zaktualizowano użytkownika",
//            'data' => $user->fresh()
//        ]);
//    }

    /**
     * Access Level
     *
     * Access Level needed: 2<br>
     *
     * Change user's access level.<br>
     * Possible values: [1,2,3]<br>
     * 1 - normal user<br>
     * 2 - moderator<br>
     * 3 - admin
     *
     * @urlParam user integer required Id of user in database
     *
     * @bodyParam access_level integer required value of access level. Example: 3
     *
     */
    public function changeAccessLevel(AccessLevelRequest $request, User $user) : JsonResponse
    {
        $user->update($request->validated());

        return response()->json([
            "message" => "Pomyślnie zmieniono poziom dostępu"
        ]);
    }

//    public function forgot(Request $request){
//        $credentials = $request->validate(['email' => 'required|email']);
//        $user = User::whereEmail($request->input('email'))->first();
//        if($user){
//            if($user->facebook_user){
//                return response()->json(['message' => "Użytkownik zarejestrowany przez facebooka. Nie można zresetować hasła."], 403);
//            }
//        }
//
//        Password::sendResetLink($credentials);
//
//        return response()->json(['message' => "Wysłano link resetujacy hasło."]);
//    }

    public function reset(Request $request){
        $credentials = request()->validate([
            'login' => 'required|string',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["message" => "Invalid token provided"], 400);
        }

        return response()->json(["message" => "Password has been successfully changed"]);
    }
}
