<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialMedia\SocialMediaRequest;
use App\Models\SocialMedia;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use App\Enums\ValidationErrorCode;

class SocialMediaController extends Controller
{
    use ResponseHandler;
    /**
     * @OA\Get(
     *     path="/api/socialMedia",
     *     tags={"SocialMedia"},
     *     summary="Get all SocialMedia",
     *      security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Everything OK"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access Denied"
     *     )
     * )
     */
    public function index(Request $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);
        // tenancy()->initialize($init['tenant']);
        $socialMedia = SocialMedia::all();
        return response()->json($this->success($socialMedia));
    }

    /**
     * @OA\Get(
     *     path="/api/socialMedia/getSocialMedia",
     *     tags={"SocialMedia"},
     *     summary="Get  SocialMedia",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Everything OK"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access Denied"
     *     )
     * )
     */
    public function getSocialMedia(Request $request)
    {
        $tenantFunction = new  TenancyFunction;
        $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $socialMedia = SocialMedia::where('restaurant_id', $init['idRestaurant'])->get();
        return response()->json($this->success($socialMedia));
    }
    /**
     * @OA\Post(
     *     path="/api/socialMedia/store",
     *     tags={"SocialMedia"},
     *     description="" ,
     *     security={{"sanctum":{}}},
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
     *                          property="type",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="value",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"name",
     *                     "type":"0",
     *                     "value":"value",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="type", type="string", example="0"),
     *              @OA\Property(property="value", type="string", example="value"),
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
    public function store(Request $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);
        // tenancy()->initialize($init['tenant']);
        $tenantFunction = new  TenancyFunction;
        $init = $tenantFunction->initializeTenant($request);
        $input = $request->all();
        $input['restaurant_id'] = $init['idRestaurant'];
        $socialMedia = SocialMedia::create($input);
        return response()->json($this->success($socialMedia));
    }
    /**
     * @OA\Get(
     *     path="/api/socialMedia/show/{id}",
     *     tags={"SocialMedia"},
     *     summary="Get  SocialMedia",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Everything OK"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access Denied"
     *     )
     * )
     */
    public function show($id, Request $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);
        // tenancy()->initialize($init['tenant']);
        $socialMedia = SocialMedia::where('id', $id)->first();
        return response()->json($this->success($socialMedia));
    }
    /**
     * @OA\Post(
     *     path="/api/socialMedia/update/{id}",
     *     tags={"SocialMedia"},
     *     description="" ,
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
     *                          property="restaurant_id",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="type",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="value",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"name",
     *                     "type":"0",
     *                     "value":"value",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="type", type="string", example="0"),
     *              @OA\Property(property="value", type="string", example="value"),
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
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);
        // tenancy()->initialize($init['tenant']);
        $socialMedia = SocialMedia::where('id', $id)->first();
        $tenantFunction = new  TenancyFunction;
        $init = $tenantFunction->initializeTenant($request);
        $input = $request->all();
        if ($request->has('value')) {
            if ($input['value'] != $socialMedia['value']) {
                $value = SocialMedia::where('value', $input['value'])->first();
                if ($value) {
                    return response()->json($this->error(new ValidationErrorCode(ValidationErrorCode::Unique), ['value' => [new ValidationErrorCode(ValidationErrorCode::Unique)]]), 422);
                }
            }
        }
        $input['restaurant_id'] = $init['idRestaurant'];
        $socialMedia->update($input);
        return response()->json($this->success($socialMedia));
    }
    /**
     * @OA\Delete(
     *     path="/api/socialMedia/delete/{id}",
     *     tags={"SocialMedia"},
     *     summary="delete  SocialMedia",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Everything OK"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access Denied"
     *     )
     * )
     */
    public function delete($id, Request $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);
        // tenancy()->initialize($init['tenant']);
        $socialMedia = SocialMedia::where('id', $id)->first();
        $socialMedia->delete();
        return response()->json($this->success());
    }
}
