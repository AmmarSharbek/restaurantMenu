<?php

namespace App\Http\Controllers;

use App\Http\Requests\Style\StyleRequest;
use App\Models\Style;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;

class StyleController extends Controller
{
    use ResponseHandler;
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/style",
     *     tags={"Style"},
     *     summary="Get all Branch",
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
        $style = Style::all();
        return response()->json($this->success($style));
    }

    /**
     * @OA\Post(
     *     path="/api/style/store",
     *     tags={"Style"},
     *     description="" ,
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
     *                          property="primary_font_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="secondary_font_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="background_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="shadow_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="primary_category_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="secondary_category_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="price_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="price_offer_color",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "restaurant_id":"restaurant_id",
     *                     "primary_font_color":"primary_font_color",
     *                     "secondary_font_color":"secondary_font_color",
     *                     "background_color":"background_color",
     *                     "shadow_color":"shadow_color",
     *                     "primary_category_color":"primary_category_color",
     *                     "secondary_category_color":"secondary_category_color",
     *                     "price_color":"price_color",
     *                     "price_offer_color":"price_offer_color",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="restaurant_id", type="integr", example="restaurant_id"),
     *              @OA\Property(property="primary_font_color", type="string", example="primary_font_color"),
     *              @OA\Property(property="secondary_font_color", type="string", example="secondary_font_color"),
     *              @OA\Property(property="background_color", type="string", example="background_color"),
     *              @OA\Property(property="shadow_color", type="string", example="shadow_color"),
     *              @OA\Property(property="primary_category_color", type="string", example="primary_category_color"),
     *              @OA\Property(property="secondary_category_color", type="string", example="secondary_category_color"),
     *              @OA\Property(property="price_color", type="string", example="price_color"),
     *              @OA\Property(property="price_offer_color", type="string", example="price_offer_color"),
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
    public function store(StyleRequest $request)
    {
        $style = Style::create($request->all());
        return response()->json($this->success($style));
    }

    /**
     * @OA\Get(
     *     path="/api/style/show/{id}",
     *     tags={"Style"},
     *     summary="Get  Style",
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
        $style = Style::where('id', $id)->first();
        return response()->json($this->success($style));
    }

    /**
     * @OA\Post(
     *     path="/api/style/update/{id}",
     *     tags={"Style"},
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
     *                      @OA\Property(
     *                          property="restaurant_id",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="primary_font_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="secondary_font_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="background_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="shadow_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="primary_category_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="secondary_category_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="price_color",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="price_offer_color",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "restaurant_id":"restaurant_id",
     *                     "primary_font_color":"primary_font_color",
     *                     "secondary_font_color":"secondary_font_color",
     *                     "background_color":"background_color",
     *                     "shadow_color":"shadow_color",
     *                     "primary_category_color":"primary_category_color",
     *                     "secondary_category_color":"secondary_category_color",
     *                     "price_color":"price_color",
     *                     "price_offer_color":"price_offer_color",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="restaurant_id", type="integr", example="restaurant_id"),
     *              @OA\Property(property="primary_font_color", type="string", example="primary_font_color"),
     *              @OA\Property(property="secondary_font_color", type="string", example="secondary_font_color"),
     *              @OA\Property(property="background_color", type="string", example="background_color"),
     *              @OA\Property(property="shadow_color", type="string", example="shadow_color"),
     *              @OA\Property(property="primary_category_color", type="string", example="primary_category_color"),
     *              @OA\Property(property="secondary_category_color", type="string", example="secondary_category_color"),
     *              @OA\Property(property="price_color", type="string", example="price_color"),
     *              @OA\Property(property="price_offer_color", type="string", example="price_offer_color"),
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
    public function update(StyleRequest $request, $id)
    {
        $style = Style::where('id', $id)->first();
        $input = $request->all();
        $style->update($input);
        return response()->json($this->success($style));
    }
    /**
     * @OA\Delete(
     *     path="/api/style/delete/{id}",
     *     tags={"Style"},
     *     summary="delete  Style",
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
        $style = Style::where('id', $id)->first();
        $style->delete();
        return response()->json($this->success());
    }
}
