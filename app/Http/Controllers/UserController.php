<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Enums\ValidationErrorCode;

class UserController extends Controller
{
    use ResponseHandler;

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get All Users",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
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
        $user = User::all();
        foreach ($user as $data) {
            $password = decrypt($data['password']);
            $data['password'] = $password;
        }
        return response()->json($this->success($user));
    }

    /**
     * @OA\Post(
     *     path="/api/user/update/{id}",
     *     tags={"Users"},
     *     description=" " ,
     *     security={{"sanctum":{}}},
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
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="isAdmin",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="sumPermessions",
     *                          type="integer"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"name",
     *                     "phone":"phone",
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
     *              @OA\Property(property="phone", type="string", example="phone"),
     *              @OA\Property(property="password", type="string", example="password"),
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
        $user = User::where('id', $id)->first();
        $input = $request->all();
        if ($request->has('name')) {
            if ($input['name'] != $user['name']) {
                $name = User::where('name', $input['name'])->first();
                if ($name) {
                    return response()->json($this->error(new ValidationErrorCode(ValidationErrorCode::Unique), ['name' => [new ValidationErrorCode(ValidationErrorCode::Unique)]]), 422);
                }
            }
        }
        if ($request->has('phone')) {
            if ($input['phone'] != $user['phone']) {
                $phone = User::where('phone', $input['phone'])->first();
                if ($phone) {
                    return response()->json($this->error(new ValidationErrorCode(ValidationErrorCode::Unique), ['phone' => [new ValidationErrorCode(ValidationErrorCode::Unique)]]), 422);
                }
            }
        }
        if ($request->has('password')) {
            $password = Crypt::encrypt($request->password);
            $input['password'] = $password;
            // return $input['userPassTenant'];
        }
        $user->update($input);
        $user['password'] = decrypt($user['password']);
        return response()->json($this->success($user));
    }

    /**
     * @OA\Delete(
     *     path="/api/user/destroy/{id}",
     *     summary="Delete User",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
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
        $user = User::find($id);
        $user->delete();
        return response()->json($this->success($user));
    }
}
