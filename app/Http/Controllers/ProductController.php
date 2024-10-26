<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductRequest;
use App\Models\Branch;
use App\Models\Menu;
use App\Models\Product;
use App\Models\Restaurant;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Option;
use App\Models\SubOption;
use App\Models\StatisticsVisit;

class ProductController extends Controller
{
    use ResponseHandler;
    /**
     * @OA\Get(
     *     path="/api/product",
     *     tags={"Product"},
     *     summary="Get all Product",
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
        $product = Product::all();
        return response()->json($this->success($product));
    }
    /**
     * @OA\Get(
     *     path="/api/product/getProduct/{branch_id}",
     *     tags={"Product"},
     *     summary="Get  Product",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="branch_id",
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
    public function getProduct(Request $request, $branch_id)
    {
        $menu = Menu::where('branch_id', $branch_id)->get();
        if (sizeof($menu) == 0) {
            return response()->json($this->success($menu));
        }
        $quere = DB::table('categories');
        $quere->where(function ($quere) use ($menu) {
            foreach ($menu as $data) {
                $quere->orWhere('menu_id', $data['id']);
            }
        });
        $category = $quere->get();
        $quere1 = DB::table('products')->orderBy('sortNum');
        $quere1->where(function ($quere1) use ($category) {
            foreach ($category as $data) {
                $quere1->orWhere('category_id', $data->id);
            }
        });
        return response()->json($this->success($quere1->get()));
    }

    /**
     * @OA\Get(
     *     path="/api/product/getProductWithOption/{category_id}",
     *     tags={"Product"},
     *     summary="Get  Product",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="category_id",
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
    public function getProductWithOption(Request $request, $category_id)
    {
        $input = $request->all();
        // $domain = Domain::where('domain', $input['domain'])->first();
        // $tenant = Tenant::where('id', $domain['tenant_id'])->first();
        // tenancy()->initialize($tenant);
        $arr = [];
        $product = Product::where('category_id', $category_id)->orderBy('sortNum')->get();
        foreach ($product as $data) {
            $option = Option::where('product_id', $data['id'])->get();
            foreach ($option as $op) {
                $subOption = SubOption::where('option_id', $op['id'])->get();
                $op['subOption'] = $subOption;
            }
            // return $option;
            $data['option'] = $option;
        }
        return response()->json($this->success($product));
    }
    /**
     * @OA\Get(
     *     path="/api/product/getProductWithOptionById/{product_id}",
     *     tags={"Product"},
     *     summary="Get  Product",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="product_id",
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
    public function getProductWithOptionById(Request $request, $product_id)
    {
        $product = Product::where('id', $product_id)->first();

        $option = Option::where('product_id', $product_id)->get();
        foreach ($option as $op) {
            $subOption = SubOption::where('option_id', $op['id'])->get();
            $op['subOption'] = $subOption;
        }
        // return $option;
        $product['option'] = $option;
        return response()->json($this->success($product));
    }
    /**
     * @OA\Post(
     *     path="/api/product/store",
     *     tags={"Product"},
     *     description="" ,
     *     security={{"sanctum":{}}},
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
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $input = $request->all();
        $image_path = "";
        if ($request->has('image')) {
            $image_path = $request->file('image')->store('image', 'public_uploads');
        } else {
            $category = Category::where('id', $input['category_id'])->first();
            $menu = Menu::where('id', $category->menu_id)->first();
            $branch = Branch::where('id', $menu->branch_id)->first();
            $restaurant = Restaurant::where('id', $branch->restaurant_id)->first();
            if ($restaurant->default_image != null) {
                $image_path = $restaurant->default_image;
            }
        }
        $input['image'] = $image_path;
        $product = Product::create($input);
        $category = Category::where('id', $input['category_id'])->first();
        $product['category'] = $category;
        return response()->json($this->success($product));
    }

    /**
     * @OA\Get(
     *     path="/api/product/show/{id}",
     *     tags={"Product"},
     *     summary="Get  Product",
     *     security={{"sanctum":{}}},
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
        $product = Product::where('id', $id)->first();
        
        return response()->json($this->success($product));
    }


    /**
     * @OA\Post(
     *     path="/api/product/update/{id}",
     *     tags={"Product"},
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
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $product = Product::where('id', $id)->first();
        $input = $request->all();
        if ($request->has('deleteImage') && $input['deleteImage'] == 1) {
            Storage::disk('public_uploads')->delete($product->image);
            $input['image'] = "";
        }
        if ($request->has('image')) {
            Storage::disk('public_uploads')->delete($product->image);
            $image_path = $request->file('image')->store('image', 'public_uploads');
            $input['image'] = $image_path;
        }
        $product->update($input);
        $category = Category::where('id', $product->category_id)->first();
        $product['category'] = $category;
        return response()->json($this->success($product));
    }

    /**
     * @OA\Delete(
     *     path="/api/product/delete/{id}",
     *     tags={"Product"},
     *     summary="delete  Product",
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
        $product = Product::where('id', $id)->first();
        Storage::disk('public_uploads')->delete($product->image);
        $product->delete();
        return response()->json($this->success());
    }

    /**
     * @OA\Post(
     *     path="/api/product/sortPrdouct",
     *     tags={"Product"},
     *     description="Sort products",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Request body for sorting products",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="arr",
     *                     type="array",
     *                     description="Array of products with sort order",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             description="ID of the product to sort"
     *                         ),
     *                         @OA\Property(
     *                             property="sortNum",
     *                             type="integer",
     *                             description="New sort number for the product"
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products sorted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="Invalid request parameters")
     *         )
     *     )
     * )
     */

    public function sortPrdouct(Request $request)
    {
        $input = $request->all();
        foreach ($input["arr"] as $data) {
            $product = Product::where('id', $data["id"])->first();
            $product->sortNum = $data["sortNum"];
            $product->save();
        }
    }

    /**
     * @OA\Get(
     *     path="/api/product/getsortPrdouct/{category_id}",
     *     tags={"Product"},
     *     description="Get sorted products by category ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="category_id",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sorted products retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function getsortPrdouct(Request $request, $category_id)
    {
        $product = Product::where('category_id', $category_id)->orderBy('sortNum')->get();
        return response()->json($this->success($product));
    }
    
  
}
