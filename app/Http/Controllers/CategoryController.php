<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryRequest;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    use ResponseHandler;
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/category",
     *     tags={"Category"},
     *     summary="Get all Category",
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
    {
        $category = Category::all();
        return response()->json($this->success($category));
    }
    /**
     * @OA\Get(
     *     path="/api/category/getCategory/{branch_id}",
     *     tags={"Category"},
     *     summary="Get  Category",
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
    public function getCategory(Request $request, $branch_id)
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
        // $quere = DB::table('categories');
        // $arr = [];
        // foreach ($menu as $data) {
        //     $category =  Category::where('menu_id', $data['id'])->get();
        //     $arr[] = $category;
        // }
        return response()->json($this->success($quere->get()));
    }

    /**
     * @OA\Post(
     *     path="/api/category/store",
     *     tags={"Category"},
     *     description="" ,
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="menu_id",
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
     *                          property="image",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "menu_id":"menu_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "image":"image",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="menu_id", type="integr", example="menu_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="image", type="string", example="image"),
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
    public function store(CategoryRequest $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $input = $request->all();
        $image_path = "";
        if ($request->has('image')) {
            $image_path = $request->file('image')->store('image', 'public_uploads');
        } else {
            $menu = Menu::where('id', $input['menu_id'])->first();
            $branch = Branch::where('id', $menu->branch_id)->first();
            $restaurant = Restaurant::where('id', $branch->restaurant_id)->first();
            if ($restaurant->default_image != null) {
                $image_path = $restaurant->default_image;
            }
        }
        $input['image'] = $image_path;
        $category = Category::create($input);
        $menu = Menu::where('id', $input['menu_id'])->first();
        $category['menu'] = $menu;
        return response()->json($this->success($category));
    }

    /**
     * @OA\Get(
     *     path="/api/category/show/{id}",
     *     tags={"Category"},
     *     summary="Get  Category",
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
        $category = Category::where('id', $id)->first();
        return response()->json($this->success($category));
    }

    /**
     * @OA\Post(
     *     path="/api/category/update/{id}",
     *     tags={"Category"},
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
     *                          property="menu_id",
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
     *                          property="image",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "menu_id":"menu_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "image":"image",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="menu_id", type="integr", example="menu_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="image", type="string", example="image"),
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
    public function update(CategoryRequest $request, $id)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $category = Category::where('id', $id)->first();
        $input = $request->all();
        if ($request->has('image')) {
            Storage::disk('public_uploads')->delete($category->image);
            $image_path = $request->file('image')->store('image', 'public_uploads');
            $input['image'] = $image_path;
        }
        $category->update($input);
        $menu = Menu::where('id', $category->menu_id)->first();
        $category['menu'] = $menu;
        return response()->json($this->success($category));
    }
    /**
     * @OA\Delete(
     *     path="/api/category/delete/{id}",
     *     tags={"Category"},
     *     summary="delete  Category",
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
        $category = Category::where('id', $id)->first();
        Storage::disk('public_uploads')->delete($category->image);
        $category->delete();
        return response()->json($this->success());
    }
}
