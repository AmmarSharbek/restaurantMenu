<?php

namespace App\Http\Controllers;

use App\Enums\ErrorCode;
use App\Http\Requests\UserTenant\UserTenantLoginRequest;
use App\Http\Requests\UserTenant\UserTenantRegisterRequest;
use App\Models\Tenant;
use App\Models\TenantProfile;
use App\Models\UserTenant;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;

class UserTenantController extends Controller
{
    use ResponseHandler;
    /**
     * Register
     * @OA\Post(
     *     path="/api/registerTenant",
     *     tags={"UserTenant"},
     *     description=" if(type) {(0=>ادمن) , (1=>عادي)}" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="userNameTenant",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="userPassTenant",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="isAdmin",
     *                          type="boolean"
     *                      ),
     *                      @OA\Property(
     *                          property="sumPermessions",
     *                          type="integer"
     *                      ),
     *                 ),
     *                 example={
     *                     "userNameTenant":"userNameTenant",
     *                     "userPassTenant":"userPassTenant",
     *                     "phone":"phone",
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
     *              @OA\Property(property="userNameTenant", type="string", example="userNameTenant"),
     *              @OA\Property(property="userPassTenant", type="string", example="userPassTenant"),
     *              @OA\Property(property="phone", type="string", example="phone"),
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

    public function register(UserTenantRegisterRequest $request)
    {

        // return $tenant;
        $id_Tenant = "";
        if ($request->has('idTenant')) {
            $id_Tenant = $request->idTenant;
        } else {
            $authorizationHeader = $request->header('Authorization');

            if (preg_match('/Bearer\s+(.+)/', $authorizationHeader, $matches)) {
                $tokenValue = $matches[1]; // This will contain the token value
                // You can now work with the $tokenValue as needed
                $token = PersonalAccessToken::findToken($tokenValue);
                $id_Tenant = $token->name;
            }
        }
        $userTenant = UserTenant::create([
            'idTenant' => $id_Tenant,
            'userNameTenant' => $request->userNameTenant,
            "userPassTenant" => Hash::make($request->userPassTenant),
            "phone" => $request->phone,
            "isAdmin" => $request->isAdmin,
            "sumPermessions" => $request->sumPermessions,
        ]);

        $response = [
            'user' => $userTenant
        ];
        return response()->json($this->success($response));
    }

    /**
     * login
     * @OA\Post(
     *     path="/api/loginTenant",
     *     tags={"UserTenant"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *
     *                      @OA\Property(
     *                          property="userPassTenant",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "phone":"phone",
     *                     "userPassTenant":"userPassTenant",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="phone", type="string", example="phone"),
     *              @OA\Property(property="userPassTenant", type="string", example="userPassTenant"),
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
    public function login(UserTenantLoginRequest $request)
    {
        $userTenant = UserTenant::where('phone', $request->phone)->first();

        //check password

        if (!$userTenant) {
            return response()->json($this->error(new ErrorCode(ErrorCode::CredentialsError)));
            // return $this->sendError('Please validate error', 'user is not found', 405);
        } else if (!(Hash::check($request->userPassTenant, $userTenant->userPassTenant))) {
            // return [Hash::make($request->userPassTenant), $userTenant->userPassTenant];
            return response()->json($this->error(new ErrorCode(ErrorCode::WorngPassword)));
        }

        $token = $userTenant->createToken($userTenant->idTenant)->plainTextToken;
        $token1 = PersonalAccessToken::findToken($token);
        $token1->expires_at = $token1->created_at->addMonths(1);
        $response = [
            'userTenant' => $userTenant,
            'token' => $token,
            'expires_at' => $token1->expires_at
        ];
        return response()->json($this->success($response));
    }

    /**
     * me
     * @OA\Get(
     *     path="/api/meTenant",
     *     tags={"UserTenant"},
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
    public function me()
    {

        return response()->json($this->success(auth()->user()));
    }

    /**
     * refresh
     * @OA\Get(
     *     path="/api/refreshTenant",
     *     tags={"UserTenant"},
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
        $authorizationHeader = $request->header('Authorization');

        if (preg_match('/Bearer\s+(.+)/', $authorizationHeader, $matches)) {
            $tokenValue = $matches[1]; // This will contain the token value
            // You can now work with the $tokenValue as needed
            $token = PersonalAccessToken::findToken($tokenValue);
            $id_Tenant = $token->name;
        }

        $user = $request->user();
        $request->user()->tokens()->delete();

        return response()->json([
            'user' => $user,
            'token' => $request->user()->createToken($id_Tenant)->plainTextToken,
        ]);
    }

    /**
     * logout
     * @OA\Post(
     *     path="/api/logoutTenant",
     *     tags={"UserTenant"},
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
