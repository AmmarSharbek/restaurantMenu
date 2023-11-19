<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\BaseController as AuthBaseController;
use App\Http\Requests\User\UserLoginRequest;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use ResponseHandler;

    // public function __construct()
    // {
    //     $this->middleware('auth.isAdmin');
    // }

    /**
     * Register
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     description=" if(type) {(0=>ادمن) , (1=>عادي)}" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="isAdmin",
     *                          type="boolean"
     *                      ),
     *                      @OA\Property(
     *                          property="sumPermessions",
     *                          type="integer"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"name",
     *                     "email":"email",
     *                     "password":"password",
     *                     "isAdmin":"isAdmin",
     *                     "sumPermessions":"sumPermessions",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="email", type="string", example="email"),
     *              @OA\Property(property="password", type="string", example="password"),
     *              @OA\Property(property="isAdmin", type="boolean", example="isAdmin"),
     *              @OA\Property(property="sumPermessions", type="integer", example="sumPermessions"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="CredentialsError = 1;Required = 100;MaxLength255 = 101;Unique = 103;"),
     *          )
     *      )
     * )
     */
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            "password" => Hash::make($request->password),
            "isAdmin" => $request->isAdmin,
            "sumPermessions" => $request->sumPermessions,
        ]);
        $token = $user->createToken('Api Token of')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json($this->success($response));
    }


    /**
     * login
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"name",
     *                     "password":"password",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="password", type="string", example="password"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="CredentialsError = 1;Required = 100;MaxLength255 = 101;"),
     *          )
     *      )
     * )
     */

    public function login(UserLoginRequest $request)
    {
        $credentials = request(['name', 'password']);
        // return auth()->attempt($credentials);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json($this->error(new ErrorCode(ErrorCode::CredentialsError)));
        }

        $user = Auth::user();
        $token = $user->createToken('Api Token')->plainTextToken;
        $token1 = PersonalAccessToken::findToken($token);
        $token1->expires_at = $token1->created_at->addMonths(1);
        $response = [
            'user' => $user,
            'token' => $token,
            'expires_at' => $token1->expires_at
        ];
        return response()->json($this->success($response));
    }

    /**
     * me
     * @OA\Get(
     *     path="/api/me",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */

    public function me(Request $request)
    {
        return response()->json($this->success(auth()->user()));
    }

    /**
     * refresh
     * @OA\Get(
     *     path="/api/refresh",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $request->user()->tokens()->delete();

        return response()->json([
            'user' => $user,
            'token' => $request->user()->createToken('Api Token of')->plainTextToken,
        ]);
    }

    /**
     * logout
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}
