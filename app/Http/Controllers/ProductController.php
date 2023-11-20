<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ResponseHandler;
    /**
     * @OA\Get(
     *     path="/api/product",
     *     tags={"Product"},
     *     summary="Get all Product",
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
        $product = Product::all();
        return response()->json($this->success($product));
    }

    /**
     * @OA\Post(
     *     path="/api/product/store",
     *     tags={"Product"},
     *     description="" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="category_id",
     *                          type="integer",
     *                      ),
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
     *                          property="image",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="double",
     *                      ),
     *                      @OA\Property(
     *                          property="price_offer",
     *                          type="double",
     *                      ),
     *                      @OA\Property(
     *                          property="common",
     *                          type="boolean",
     *                      ),
     *                      @OA\Property(
     *                          property="new",
     *                          type="boolean",
     *                      ),
     *                      @OA\Property(
     *                          property="hidden",
     *                          type="boolean",
     *                      ),
     *                      @OA\Property(
     *                          property="unavailable",
     *                          type="boolean",
     *                      ),
     *                 ),
     *                 example={
     *                     "category_id":"category_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "description_ar":"description_ar",
     *                     "description_en":"description_en",
     *                     "image":"image",
     *                     "price":"0",
     *                     "price_offer":"0",
     *                     "common":"0",
     *                     "new":"0",
     *                     "hidden":"0",
     *                     "unavailable":"0",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="category_id", type="integer", example="category_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="description_ar", type="string", example="description_ar"),
     *              @OA\Property(property="description_en", type="string", example="description_en"),
     *              @OA\Property(property="image", type="string", example="image"),
     *              @OA\Property(property="price", type="double", example="price"),
     *              @OA\Property(property="price_offer", type="double", example="price_offer"),
     *              @OA\Property(property="common", type="boolean", example="common"),
     *              @OA\Property(property="new", type="boolean", example="new"),
     *              @OA\Property(property="hidden", type="boolean", example="hidden"),
     *              @OA\Property(property="unavailable", type="boolean", example="unavailable"),
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
    public function store(ProductRequest $request)
    {
        $input = $request->all();
        $image_path = "";
        if ($request->has('data.image')) {
            $image_path = $request->file('image')->store('image', 'public_uploads');
        }
        $input['image'] = $image_path;
        $product = Product::create($input);
        return response()->json($this->success($product));
    }

    /**
     * @OA\Get(
     *     path="/api/product/show/{id}",
     *     tags={"Product"},
     *     summary="Get  Product",
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
        $product = Product::where('id', $id)->first();
        return response()->json($this->success($product));
    }

    /**
     * @OA\Post(
     *     path="/api/product/update/{id}",
     *     tags={"Product"},
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
     *                          property="category_id",
     *                          type="integer",
     *                      ),
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
     *                          property="image",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="double",
     *                      ),
     *                      @OA\Property(
     *                          property="price_offer",
     *                          type="double",
     *                      ),
     *                      @OA\Property(
     *                          property="common",
     *                          type="boolean",
     *                      ),
     *                      @OA\Property(
     *                          property="new",
     *                          type="boolean",
     *                      ),
     *                      @OA\Property(
     *                          property="hidden",
     *                          type="boolean",
     *                      ),
     *                      @OA\Property(
     *                          property="unavailable",
     *                          type="boolean",
     *                      ),
     *                 ),
     *                 example={
     *                     "category_id":"category_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "description_ar":"description_ar",
     *                     "description_en":"description_en",
     *                     "image":"image",
     *                     "price":"0",
     *                     "price_offer":"0",
     *                     "common":"0",
     *                     "new":"0",
     *                     "hidden":"0",
     *                     "unavailable":"0",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="category_id", type="integer", example="category_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="description_ar", type="string", example="description_ar"),
     *              @OA\Property(property="description_en", type="string", example="description_en"),
     *              @OA\Property(property="image", type="string", example="image"),
     *              @OA\Property(property="price", type="double", example="price"),
     *              @OA\Property(property="price_offer", type="double", example="price_offer"),
     *              @OA\Property(property="common", type="boolean", example="common"),
     *              @OA\Property(property="new", type="boolean", example="new"),
     *              @OA\Property(property="hidden", type="boolean", example="hidden"),
     *              @OA\Property(property="unavailable", type="boolean", example="unavailable"),
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
    public function update(ProductRequest $request, $id)
    {
        $product = Product::where('id', $id)->first();
        $input = $request->all();
        Storage::disk('public_uploads')->delete($product->image);
        // $imageUrl = asset('storage/' . $image->image);

        // return $this->sendResponse($imageUrl, "");
        $image_path = $request->file('image')->store('image', 'public_uploads');
        $input['image'] = $image_path;
        $product->update($input);
        return response()->json($this->success($product));
    }

    /**
     * @OA\Delete(
     *     path="/api/product/delete/{id}",
     *     tags={"Product"},
     *     summary="delete  Product",
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
        $product = Product::where('id', $id)->first();
        Storage::disk('public_uploads')->delete($product->image);
        $product->delete();
        return response()->json($this->success());
    }
}
