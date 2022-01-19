<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

//use App\Http\Requests\User\AccessLevelRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * @group User Management
 *
 * @authenticated
 *
 * API's for user resource management
 *
 */
class UserController extends Controller
{
    /**
     * Returns a list of users
     *
     * User roles: user, moderator, admin<br>
     *
     * Display a paginated(by 20) listing of the users.<br>
     *
     * @queryParam page integer Non required field - self explanatory
     * @queryParam limit integer Non required field - self explanatory
     *
     * @response 201 {
     * "data": [
     * {
     * "id": 1,
     * "name": "Amir",
     * "surname": "Sauer",
     * "date_of_birth": "2012-11-05",
     * "login": "moderator",
     * "role": "moderator"
     * },
     * {
     * "id": 2,
     * "name": "Trycia",
     * "surname": "Parisian",
     * "date_of_birth": "2009-02-04",
     * "login": "admin",
     * "role": "admin"
     * },
     * {
     * "id": 3,
     * "name": "Holie",
     * "surname": "Romaguera",
     * "date_of_birth": "1998-10-11",
     * "login": "boehm.serena",
     * "role": "user"
     * },
     * {
     * "id": 4,
     * "name": "Myrna",
     * "surname": "Yost",
     * "date_of_birth": "1984-09-15",
     * "login": "vschneider",
     * "role": "user"
     * },
     * {
     * "id": 5,
     * "name": "Edison",
     * "surname": "Ritchie",
     * "date_of_birth": "2003-07-05",
     * "login": "rschmeler",
     * "role": "user"
     * },
     * {
     * "id": 6,
     * "name": "Tyrel",
     * "surname": "Schimmel",
     * "date_of_birth": "1992-10-24",
     * "login": "emory76",
     * "role": "user"
     * },
     * {
     * "id": 7,
     * "name": "Kiel",
     * "surname": "Rutherford",
     * "date_of_birth": "1990-02-24",
     * "login": "lakin.hallie",
     * "role": "user"
     * },
     * {
     * "id": 8,
     * "name": "Arlene",
     * "surname": "Weimann",
     * "date_of_birth": "1970-03-04",
     * "login": "wisoky.gillian",
     * "role": "user"
     * },
     * {
     * "id": 9,
     * "name": "Deborah",
     * "surname": "Murazik",
     * "date_of_birth": "2012-03-08",
     * "login": "llebsack",
     * "role": "user"
     * },
     * {
     * "id": 10,
     * "name": "Gerhard",
     * "surname": "Mayer",
     * "date_of_birth": "2015-08-04",
     * "login": "athena94",
     * "role": "user"
     * },
     * {
     * "id": 11,
     * "name": "Evans",
     * "surname": "Dooley",
     * "date_of_birth": "1978-01-29",
     * "login": "roy25",
     * "role": "user"
     * },
     * {
     * "id": 12,
     * "name": "Caroline",
     * "surname": "Mante",
     * "date_of_birth": "2020-06-17",
     * "login": "bcollins",
     * "role": "user"
     * },
     * {
     * "id": 13,
     * "name": "Francis",
     * "surname": "Schiller",
     * "date_of_birth": "1991-09-28",
     * "login": "torey65",
     * "role": "user"
     * },
     * {
     * "id": 14,
     * "name": "Dina",
     * "surname": "Dietrich",
     * "date_of_birth": "1986-03-05",
     * "login": "ilehner",
     * "role": "user"
     * },
     * {
     * "id": 15,
     * "name": "Dexter",
     * "surname": "Bruen",
     * "date_of_birth": "2006-02-08",
     * "login": "bstamm",
     * "role": "user"
     * },
     * {
     * "id": 16,
     * "name": "Vivian",
     * "surname": "Braun",
     * "date_of_birth": "1981-08-22",
     * "login": "jakubowski.richie",
     * "role": "user"
     * },
     * {
     * "id": 17,
     * "name": "Palma",
     * "surname": "Schneider",
     * "date_of_birth": "1983-05-25",
     * "login": "glover.johnnie",
     * "role": "user"
     * },
     * {
     * "id": 18,
     * "name": "Noe",
     * "surname": "Larson",
     * "date_of_birth": "1989-10-28",
     * "login": "kling.berry",
     * "role": "user"
     * },
     * {
     * "id": 19,
     * "name": "Charity",
     * "surname": "Adams",
     * "date_of_birth": "1977-09-24",
     * "login": "veichmann",
     * "role": "user"
     * },
     * {
     * "id": 20,
     * "name": "Cayla",
     * "surname": "Leffler",
     * "date_of_birth": "1984-08-28",
     * "login": "raegan73",
     * "role": "user"
     * }
     * ],
     * "links": {
     * "first": "http://localhost/UAM/simple-crm/public/api/users?page=1",
     * "last": "http://localhost/UAM/simple-crm/public/api/users?page=3",
     * "prev": null,
     * "next": "http://localhost/UAM/simple-crm/public/api/users?page=2"
     * },
     * "meta": {
     * "current_page": 1,
     * "from": 1,
     * "last_page": 3,
     * "links": [
     * {
     * "url": null,
     * "label": "&laquo; Previous",
     * "active": false
     * },
     * {
     * "url": "http://localhost/UAM/simple-crm/public/api/users?page=1",
     * "label": "1",
     * "active": true
     * },
     * {
     * "url": "http://localhost/UAM/simple-crm/public/api/users?page=2",
     * "label": "2",
     * "active": false
     * },
     * {
     * "url": "http://localhost/UAM/simple-crm/public/api/users?page=3",
     * "label": "3",
     * "active": false
     * },
     * {
     * "url": "http://localhost/UAM/simple-crm/public/api/users?page=2",
     * "label": "Next &raquo;",
     * "active": false
     * }
     * ],
     * "path": "http://localhost/UAM/simple-crm/public/api/users",
     * "per_page": 20,
     * "to": 20,
     * "total": 54
     * }
     * }
     *
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 20);

        return UserResource::collection(User::paginate($limit));
    }

    /**
     * @group User Management
     *
     * Return single user
     *
     * User roles: user, moderator, admin<br>
     *
     * Display the specified user by id.<br>
     *
     * @urlParam user integer required Id of user in database
     *
     * @response 201 {
     * "data": {
     * "id": 2,
     * "name": "Trycia",
     * "surname": "Parisian",
     * "date_of_birth": "2009-02-04",
     * "login": "admin",
     * "role": "admin"
     * }
     * }
     *
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update single user
     *
     * User roles: moderator, admin<br>
     *
     * Updates user's columns find by id
     *
     * @urlParam user integer required Id of user in database
     *
     * @bodyParam name string optional User's name. Example: Adam
     * @bodyParam surname string optional User's surname. Example: Kowalski
     * @bodyParam date_of_birth date optional User's date of birth. Example: 2000-10-10
     * @bodyParam role string optional User's role, possible values: user, moderator, admin. Example: moderator
     *
     * @response 200 {
     * "message": "Pomyślnie zaktualizowano użytkownika",
     * "data": {
     * "id": 3,
     * "name": "Holie",
     * "surname": "Romaguera",
     * "date_of_birth": "1998-10-11",
     * "login": "boehm.serena",
     * "deleted_at": null,
     * "created_at": "2021-12-20T13:09:01.000000Z",
     * "updated_at": "2021-12-20T13:09:48.000000Z"
     * }
     * }
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        $newRole = $request->has('role') ? $request->get('role') : null;
        if ($newRole != null) {
            $user->syncRoles([$newRole]);
        }

        return response()->json([
            'message' => "Pomyślnie zaktualizowano użytkownika",
            'data' => $user->fresh()
        ]);
    }

    /**
     * Delete single user
     *
     * User roles: admin<br>
     *
     * Remove the specified user from database.
     * May throw ModelNotFoundException if user's is not in database
     *
     * @urlParam user integer required Id of user in database
     *
     * @response 201 {
     * "message": "Pomyślnie usunięto użytkownika"
     * }
     *
     * @response 403 {
     * "message": "User does not have the right roles.",
     * "exception": "Spatie\\Permission\\Exceptions\\UnauthorizedException",
     * "file": "G:\\XAMPP\\htdocs\\UAM\\simple-crm\\vendor\\spatie\\laravel-permission\\src\\Exceptions\\UnauthorizedException.php",
     * "line": 22,
     * "trace": [...]
     * }
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
        } catch (ModelNotFoundException $exception) {
            info($exception);
        }
        return response()->json([
            "message" => "Pomyślnie usunięto użytkownika"
        ]);
    }

    /**
     * Current user
     *
     * User roles: user, moderator, admin<br>
     *
     * Display currently logged in user.<br>
     *
     * @response 201 {
     * "data": {
     * "id": 2,
     * "name": "Trycia",
     * "surname": "Parisian",
     * "date_of_birth": "2009-02-04",
     * "login": "admin",
     * "role": "admin"
     * }
     * }
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

//    public function reset(Request $request){
//        $credentials = request()->validate([
//            'login' => 'required|string',
//            'token' => 'required|string',
//            'password' => 'required|string|confirmed'
//        ]);
//
//        $reset_password_status = Password::reset($credentials, function ($user, $password) {
//            $user->password = bcrypt($password);
//            $user->save();
//        });
//
//        if ($reset_password_status == Password::INVALID_TOKEN) {
//            return response()->json(["message" => "Invalid token provided"], 400);
//        }
//
//        return response()->json(["message" => "Password has been successfully changed"]);
//    }
}
