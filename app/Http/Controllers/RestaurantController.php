<?php

namespace App\Http\Controllers;

use App\Enums\ErrorCode;
use App\Http\Requests\Restaurant\RestaurantRequest;
use App\Models\Domain;
use App\Models\Restaurant;
use App\Models\Tenant;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use App\Enums\ValidationErrorCode;

class RestaurantController extends Controller
{
    use ResponseHandler;
    /**
     * @OA\Get(
     *     path="/api/restaurant",
     *     tags={"Restaurant"},
     *     summary="Get all Restaurant",
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
    public function index(Request $request)
    { // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $restaurant = Restaurant::all();
        return response()->json($this->success($restaurant));
    }
    /**
     * @OA\Get(
     *     path="/api/restaurant/getRestaurant",
     *     tags={"Restaurant"},
     *     summary="Get  Restaurant",
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
    public function getRestaurant(Request $request)
    {
        $tenantFunction = new  TenancyFunction;
        $init = $tenantFunction->initializeTenant($request);
        // tenancy()->initialize($init['tenant']);
        $restaurant = Restaurant::where('id', $init['idRestaurant'])->first();
        if (!$restaurant || $restaurant->isActive == false) {
            return response()->json($this->error(new ErrorCode(ErrorCode::NotFound)), 422);
        }
        return response()->json($this->success($restaurant));
    }

    /**
     * @OA\Post(
     *     path="/api/restaurant/store",
     *     tags={"Restaurant"},
     *     description="" ,
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name_ar",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="name_en",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="description_ar",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="description_en",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="logo",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="default_image",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="currency",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="domin",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="isActive",
     *                          type="boolean"
     *                      ),
     *                 ),
     *                 example={
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "description_ar":"description_ar",
     *                     "description_en":"description_en",
     *                     "logo":"logo",
     *                     "default_image":"default_image",
     *                     "currency":"currency",
     *                     "domin":"domin",
     *                     "isActive":"0",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="description_ar", type="string", example="description_ar"),
     *              @OA\Property(property="description_en", type="string", example="description_en"),
     *              @OA\Property(property="logo", type="string", example="logo"),
     *              @OA\Property(property="default_image", type="string", example="default_image"),
     *              @OA\Property(property="currency", type="string", example="currency"),
     *              @OA\Property(property="domin", type="string", example="domin"),
     *              @OA\Property(property="isActive", type="string", example="0"),
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
    public function store(RestaurantRequest $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        $input = $request->all();
        // $domain = Domain::create([
        //     'domain' => $input['domin'],
        //     'tenant_id' => $init['id_Tenant']
        // ]);

        // tenancy()->initialize($init['tenant']);
         $image_path = "";
        if ($request->has('logo')) {
            $image_path = $request->file('logo')->store('image', 'public_uploads');
        }
        $input['logo'] = $image_path;
        $image_path1 = "";
        if ($request->has('default_image')) {
            $image_path1 = $request->file('default_image')->store('image', 'public_uploads');
        }
        $input['default_image'] = $image_path1;
        $restaurant = Restaurant::create($input);
        return response()->json($this->success($restaurant));
    }

    /**
     * @OA\Get(
     *     path="/api/restaurant/show/{id}",
     *     tags={"Restaurant"},
     *     summary="Get  constant",
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
        $restaurant = Restaurant::where('id', $id)->first();
        return response()->json($this->success($restaurant));
    }

    /**
     * @OA\Post(
     *     path="/api/restaurant/update/{id}",
     *     tags={"Restaurant"},
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
     *                          property="name_ar",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="name_en",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="description_ar",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="description_en",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="logo",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="default_image",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="currency",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="domin",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="isActive",
     *                          type="boolean"
     *                      ),
     *                 ),
     *                 example={
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "description_ar":"description_ar",
     *                     "description_en":"description_en",
     *                     "logo":"logo",
     *                     "default_image":"default_image",
     *                     "currency":"currency",
     *                     "domin":"domin",
     *                     "isActive":"0",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="description_ar", type="string", example="description_ar"),
     *              @OA\Property(property="description_en", type="string", example="description_en"),
     *              @OA\Property(property="logo", type="string", example="logo"),
     *              @OA\Property(property="default_image", type="string", example="default_image"),
     *              @OA\Property(property="currency", type="string", example="currency"),
     *              @OA\Property(property="domin", type="string", example="domin"),
     *              @OA\Property(property="isActive", type="string", example="0"),
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
        $restaurant = Restaurant::where('id', $id)->first();
        $input = $request->all();
        if ($request->has('domin')) {
            if ($input['domin'] != $restaurant['domin']) {
                $domin = Restaurant::where('domin', $input['domin'])->first();
                if ($domin) {
                    return response()->json($this->error(new ValidationErrorCode(ValidationErrorCode::Unique), ['domin' => [new ValidationErrorCode(ValidationErrorCode::Unique)]]), 422);
                }
            }
        }
        // if ($restaurant->domin != $input['domin']) {
        //     // tenancy()->end();
        //     $oldDomain = Domain::where('domain', $restaurant->domin)->first();
        //     $oldDomain->update([
        //         'domain' => $input['domin'],
        //         'tenant_id' => $init['id_Tenant']
        //     ]);
        //     // tenancy()->initialize($init['tenant']);
        // }
        $image_path = "";
        if ($request->has('deleteImage') && $input['deleteImage'] == 1) {
            Storage::disk('public_uploads')->delete($restaurant->logo);
            Storage::disk('public_uploads')->delete($restaurant->default_image);
            $input['logo'] = "";
            $input['default_image'] = "";
        }
        if ($request->has('logo')) {
            Storage::disk('public_uploads')->delete($restaurant->logo);
            $image_path = $request->file('logo')->store('image', 'public_uploads');
            $input['logo'] = $image_path;
        }
        if ($request->has('default_image')) {
            if ($restaurant['default_image'] != null) {
                Storage::disk('public_uploads')->delete($restaurant->default_image);
            }
            $image_path = $request->file('default_image')->store('image', 'public_uploads');
            $input['default_image'] = $image_path;
        }
        $restaurant->update($input);
        return response()->json($this->success($restaurant));
    }

    /**
     * @OA\Delete(
     *     path="/api/restaurant/delete/{id}",
     *     tags={"Restaurant"},
     *     summary="delete  Restaurant",
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

        $restaurant = Restaurant::where('id', $id)->first();
        // tenancy()->end();
        // $oldDomain = Domain::where('domain', $restaurant->domin)->first();
        // $oldDomain->delete();

        // tenancy()->initialize($init['tenant']);

        $restaurant = Restaurant::where('id', $id)->first();
        Storage::disk('public_uploads')->delete($restaurant->logo);
        $restaurant->delete();
        return response()->json($this->success());
    }
}
