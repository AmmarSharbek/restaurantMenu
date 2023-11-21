<?php

namespace App\Http\Controllers;

use App\Http\Requests\Restaurant\RestaurantRequest;
use App\Models\Restaurant;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    use ResponseHandler;
    /**
     * @OA\Get(
     *     path="/api/restaurant",
     *     tags={"Restaurant"},
     *     summary="Get all Restaurant",
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
    public function index()
    {
        $restaurant = Restaurant::all();
        return response()->json($this->success($restaurant));
    }

    /**
     * @OA\Post(
     *     path="/api/restaurant/store",
     *     tags={"Restaurant"},
     *     description="" ,
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
     *                          property="currency",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "description_ar":"description_ar",
     *                     "description_en":"description_en",
     *                     "logo":"logo",
     *                     "currency":"currency",
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
     *              @OA\Property(property="currency", type="string", example="currency"),
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
        $input = $request->all();
        $image_path = "";
        if ($request->has('logo')) {
            $image_path = $request->file('logo')->store('image', 'public_uploads');
        }
        $input['logo'] = $image_path;
        $restaurant = Restaurant::create($input);
        return response()->json($this->success($restaurant));
    }

    /**
     * @OA\Get(
     *     path="/api/restaurant/show/{id}",
     *     tags={"Restaurant"},
     *     summary="Get  constant",
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
    public function show($id)
    {
        $restaurant = Restaurant::where('id', $id)->first();
        return response()->json($this->success($restaurant));
    }

    /**
     * @OA\Post(
     *     path="/api/restaurant/update/{id}",
     *     tags={"Restaurant"},
     *     description="" ,
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
     *                     @OA\Property(
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
     *                          property="currency",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "description_ar":"description_ar",
     *                     "description_en":"description_en",
     *                     "logo":"logo",
     *                     "currency":"currency",
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
     *              @OA\Property(property="currency", type="string", example="currency"),
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
    public function update(RestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::where('id', $id)->first();
        $input = $request->all();
        Storage::disk('public_uploads')->delete($restaurant->logo);
        // $imageUrl = asset('storage/' . $image->image);

        // return $this->sendResponse($imageUrl, "");
        $image_path = $request->file('logo')->store('image', 'public_uploads');
        $input['logo'] = $image_path;
        $restaurant->update($input);
        return response()->json($this->success($restaurant));
    }

    /**
     * @OA\Delete(
     *     path="/api/restaurant/delete/{id}",
     *     tags={"Restaurant"},
     *     summary="delete  Restaurant",
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
    public function delete($id)
    {
        $restaurant = Restaurant::where('id', $id)->first();
        Storage::disk('public_uploads')->delete($restaurant->logo);
        $restaurant->delete();
        return response()->json($this->success());
    }
}
