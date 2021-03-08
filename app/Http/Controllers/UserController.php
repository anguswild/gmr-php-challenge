<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login']);
        $this->middleware('permission:get-user', ['only' => ['index']]);
        $this->middleware('permission:new-user', ['only' => ['store']]);
    }

    public $successStatus = 200;

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by username, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="application/json"
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string", format="username", example="admin"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'username' => 'required',
            'password'    => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Error de Validación'
            ];
            return response()->json($response, 400);
        }


        if (Auth::attempt(['username' => request('username'), 'password' => request('password')])) {
            $user = Auth::user();
            $tokenResult = $user->createToken('php_challenge_token');
            $success['token'] =  $tokenResult->accessToken;
            $success['token_type'] = 'Bearer';

            $success['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();

            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    /**
     * @OA\Get(
     * path="api/users/details",
     * summary="User Details",
     * description="Get user details of the current authenticated user",
     * operationId="userDetails",
     * tags={"auth"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="application/json"
     *          )
     *      ),
     *  *  * @OA\Parameter(
     *          name="Authorization",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="Bearer <Token>"
     *          )
     *      ),
     * 
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="User details",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="true"),
     *       @OA\Property(property="data", type="string", format="data", example="user details"),
     *           @OA\Property(property="message", type="string", example="User Details")
     *        )
     *     ),
     * 
     * )
     */

    public function details()
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User Details'
        ], $this->successStatus);
    }
    /**
     * @OA\Get(
     * path="api/users",
     * summary="User List",
     * description="Get a list of all the users in the api",
     * operationId="userIndex",
     * tags={"auth"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="application/json"
     *          )
     *      ),
     *  *  * @OA\Parameter(
     *          name="Authorization",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="Bearer <Token>"
     *          )
     *      ),
     * 
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="User details",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="true"),
     *       @OA\Property(property="data", type="string", format="data", example="{List of Users}"),
     *           @OA\Property(property="message", type="string", example="List of all Users")
     *        )
     *     ),
     * 
     * )
     */

    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'List of all Users'
        ], $this->successStatus);
    }
    /**
     * @OA\Get(
     * path="api/users/{id}",
     * summary="One User",
     * description="Get the details of a especific user",
     * operationId="getUser",
     * tags={"auth"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="application/json"
     *          )
     *      ),
     *  *  * @OA\Parameter(
     *          name="Authorization",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="Bearer <Token>"
     *          )
     *      ),
     * 
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="User details",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="true"),
     *       @OA\Property(property="data", type="string", format="data", example="user details"),
     *           @OA\Property(property="message", type="string", example="User Details")
     *        )
     *     ),
     * 
     * )
     */

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User Details'
        ], $this->successStatus);
    }
/**
     * @OA\Post(
     * path="/api/users",
     * summary="User Registration",
     * description="User Registration",
     * operationId="authLogin",
     * tags={"auth"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="application/json"
     *          )
     *      ),
     * @OA\Parameter(
     *          name="Authorization",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="Bearer <Token>"
     *          )
     *      ),
     * 
     * @OA\RequestBody(
     *    required=true,
     *    description="Required fields for user registration",
     *    @OA\JsonContent(
     *       required={"username","name","email","password","c_password"},
     *       @OA\Property(property="username", type="string", format="username", example="admin"),
     *       @OA\Property(property="name", type="string", format="name", example="User Test"),
     *       @OA\Property(property="password", type="string", format="password", example="12345678"),
     *       @OA\Property(property="c_password", type="string", format="password", example="12345678"),
     *    ),
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=201,
     *    description="User registration details",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="true"),
     *       @OA\Property(property="data", type="string", format="data", example="User details"),
     *           @OA\Property(property="message", type="string", example="Registered user successfully")
     *        )
     *     ),
     * )
     */

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Error de Validación'
            ];
            return response()->json($response, 400);
        }

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Registered user successfully'
        ], 201);
    }
    /**
     * @OA\Delete(
     * path="/api/users/{id}",
     * summary="User deletion",
     * description="User Deletion",
     * operationId="authLogin",
     * tags={"auth"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="application/json"
     *          )
     *      ),
     * @OA\Parameter(
     *          name="Authorization",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="Bearer <Token>"
     *          )
     *      ),
     * 
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="User deletion details",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="true"),
     *       @OA\Property(property="data", type="string", format="data", example="User details"),
     *           @OA\Property(property="message", type="string", example="User deleted successfully")
     *        )
     *     ),
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User deleted successfully'
        ], $this->successStatus);
    }
}
