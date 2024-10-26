<?php

namespace App\Http\Controllers;

use App\Enums\ErrorCode;
use App\Http\Requests\UserTenant\UserTenantLoginRequest;
use App\Http\Requests\UserTenant\UserTenantRegisterRequest;
use App\Models\Restaurant;
use App\Models\Tenant;
use App\Models\TenantProfile;
use App\Models\User;
use App\Models\UserTenant;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Crypt;
use App\Enums\ValidationErrorCode;

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
     *                          property="idRestaurant",
     *                          type="string",
     *                      ),
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
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="sumPermessions",
     *                          type="integer"
     *                      ),
     *                 ),
     *                 example={
     *                     "idRestaurant":"1",
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
     *              @OA\Property(property="idRestaurant", type="string", example="1"),
     *              @OA\Property(property="userNameTenant", type="string", example="userNameTenant"),
     *              @OA\Property(property="userPassTenant", type="string", example="userPassTenant"),
     *              @OA\Property(property="phone", type="string", example="phone"),
     *              @OA\Property(property="isAdmin", type="integer", example="isAdmin"),
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
        // $id_Tenant = "";
        // if ($request->has('idTenant')) {
        //     $id_Tenant = $request->idTenant;
        // } else {
        //     $authorizationHeader = $request->header('Authorization');

        //     if (preg_match('/Bearer\s+(.+)/', $authorizationHeader, $matches)) {
        //         $tokenValue = $matches[1]; // This will contain the token value
        //         // You can now work with the $tokenValue as needed
        //         $token = PersonalAccessToken::findToken($tokenValue);
        //         $id_Tenant = $token->name;
        //     }
        // }
        $userTenant = UserTenant::create([
            'idRestaurant' =>  $request->idRestaurant,
            'userNameTenant' => $request->userNameTenant,
            "userPassTenant" => encrypt($request->userPassTenant),
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
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            if ($request->userPassTenant != decrypt($user->password)) {
                // return [Hash::make($request->userPassTenant), $userTenant->userPassTenant];
                return response()->json($this->error(new ErrorCode(ErrorCode::WorngPassword)), 422);
            }
            if ($user->isAdmin == 0) {
                return response()->json($this->error(new ErrorCode(ErrorCode::NotAdmin)), 422);
            }
            $restaurant_id = '';
            if ($request->has('idRestaurant')) {
                $restaurant = Restaurant::where('id', $request->idRestaurant)->first();
                if ($restaurant->isActive == false) {
                    return response()->json($this->error(new ErrorCode(ErrorCode::NotFound)),422);
                }
                $restaurant_id = $request->idRestaurant;
            }

            $token = $user->createToken($restaurant_id)->plainTextToken;
            $token1 = PersonalAccessToken::findToken($token);
            $token1->expires_at = $token1->created_at->addMonths(1);
            $response = [
                'userTenant' => $user,
                'token' => $token,
                'expires_at' => $token1->expires_at
            ];
            return response()->json($this->success($response));
        }
        $userTenant = UserTenant::where('phone', $request->phone)->first();
        //check password
        if (!$userTenant) {
            return response()->json($this->error(new ErrorCode(ErrorCode::CredentialsError)),422);
            // return $this->sendError('Please validate error', 'user is not found', 405);
        } else if ($request->userPassTenant != decrypt($userTenant->userPassTenant)) {
            // return [Hash::make($request->userPassTenant), $userTenant->userPassTenant];
            return response()->json($this->error(new ErrorCode(ErrorCode::WorngPassword)), 422);
        }
        $restaurant = Restaurant::where('id', $userTenant["idRestaurant"])->first();
        // return $restaurant;

        if ($restaurant["isActive"] == 0) {
            return response()->json($this->error(new ErrorCode(ErrorCode::NotFound)), 422);
        }

        $token = $userTenant->createToken($userTenant->idRestaurant)->plainTextToken;
        $token1 = PersonalAccessToken::findToken($token);
        $token1->expires_at = $token1->created_at->addMonths(1);
        $response = [
            'userTenant' => $userTenant,
            'token' => $token,
            'expires_at' => $token1->expires_at,
            'restaurant' => $restaurant
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
    public function me(Request $request)
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
        // $authorizationHeader = $request->header('Authorization');

        // if (preg_match('/Bearer\s+(.+)/', $authorizationHeader, $matches)) {
        //     $tokenValue = $matches[1]; // This will contain the token value
        //     // You can now work with the $tokenValue as needed
        //     $token = PersonalAccessToken::findToken($tokenValue);
        //     $id_Tenant = $token->name;
        // }

        $user = $request->user();
        $idRestaurant = $user->idRestaurant;
        $request->user()->tokens()->delete();


        return response()->json([
            'user' => $user,
            'token' => $request->user()->createToken($idRestaurant)->plainTextToken,
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

    /**
     * @OA\Get(
     *     path="/api/userTenant",
     *     summary="Get All userTenant",
     *     tags={"UserTenant"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=""),
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        $userTenant = UserTenant::all();
        foreach ($userTenant as $data) {
            $userPassTenant = decrypt($data['userPassTenant']);
            $data['userPassTenant'] = $userPassTenant;
        }
        return response()->json($this->success($userTenant));
    }

    /**
     * @OA\Post(
     *     path="/api/userTenant/update/{id}",
     *     tags={"UserTenant"},
     *     description=" " ,
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                       type="object",
     *                      @OA\Property(
     *                          property="idRestaurant",
     *                          type="string",
     *                      ),
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
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="sumPermessions",
     *                          type="integer"
     *                      ),
     *                 ),
     *                 example={
     *                     "idRestaurant":"1",
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
     *              @OA\Property(property="idRestaurant", type="string", example="1"),
     *              @OA\Property(property="userNameTenant", type="string", example="userNameTenant"),
     *              @OA\Property(property="userPassTenant", type="string", example="userPassTenant"),
     *              @OA\Property(property="phone", type="string", example="phone"),
     *              @OA\Property(property="isAdmin", type="integer", example="isAdmin"),
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
    public function update(Request $request, $id)
    {
        $userTenant = UserTenant::where('id', $id)->first();
        $input = $request->all();
        if ($request->has('phone')) {
            if ($input['phone'] != $userTenant['phone']) {
                $phone = UserTenant::where('phone', $input['phone'])->first();
                if ($phone) {
                    return response()->json($this->error(new ValidationErrorCode(ValidationErrorCode::Unique), ['phone' => [new ValidationErrorCode(ValidationErrorCode::Unique)]]), 422);
                }
            }
        }
        if ($request->has('userPassTenant')) {
            $userPassTenant = Crypt::encrypt($request->userPassTenant);
            $input['userPassTenant'] = $userPassTenant;
            // return $input['userPassTenant'];
        }
        $userTenant->update($input);
        $userTenant['userPassTenant'] = decrypt($userTenant['userPassTenant']);
        return response()->json($this->success($userTenant));
    }

    /**
     * @OA\Delete(
     *     path="/api/userTenant/destroy/{id}",
     *     summary="Delete userTenant",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", description="User ID"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref=""),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     ),
     * )
     */
    public function destroy($id)
    {
        $userTenant = UserTenant::find($id);
        $userTenant->delete();
        return response()->json($this->success($userTenant));
    }
}
