<?php

namespace App\Http\Controllers;

use App\Http\Requests\Menu\MenuRequest;
use App\Models\Branch;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    use ResponseHandler;
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/menu",
     *     tags={"Menu"},
     *     summary="Get all Menu",
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

        $menu = Menu::all();
        return response()->json($this->success($menu));
    }
    /**
     * @OA\Get(
     *     path="/api/menu/getMenu/{branch_id}",
     *     tags={"Menu"},
     *     summary="Get  Menu",
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
    public function getMenu(Request $request, $branch_id)
    {

        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);
        // // tenancy()->initialize($init['tenant']);
        // // return $restaurant;
        // $branch = Branch::where('restaurant_id', $init['idRestaurant'])->where('num', 1)->first();
        $menu = Menu::where('branch_id', $branch_id)->get();
        return response()->json($this->success($menu));
    }

    /**
     * @OA\Post(
     *     path="/api/menu/store",
     *     tags={"Menu"},
     *     description="" ,
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="branch_id",
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
     *                     "branch_id":"branch_id",
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
     *              @OA\Property(property="branch_id", type="integr", example="branch_id"),
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
    public function store(MenuRequest $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);
        // // tenancy()->initialize($init['tenant']);
        // $restaurant = Restaurant::where('domin', $init['id_Tenant'])->first();
        // // return $restaurant;
        // $branch = Branch::where('restaurant_id', $restaurant['id'])->where('num', 1)->first();
        $input = $request->all();
        // $input['branch_id'] = $branch->id;
        $image_path = "";
        if ($request->has('image')) {
            $image_path = $request->file('image')->store('image', 'public_uploads');
        } else {
            $branch = Branch::where('id', $input['branch_id'])->first();
            $restaurant = Restaurant::where('id', $branch->restaurant_id)->first();
            if ($restaurant->default_image != null) {
                $image_path = $restaurant->default_image;
            }
        }
        $input['image'] = $image_path;
        $menu = Menu::create($input);
        return response()->json($this->success($menu));
    }

    /**
     * @OA\Get(
     *     path="/api/menu/show/{id}",
     *     tags={"Menu"},
     *     summary="Get  Menu",
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
        $menu = Menu::where('id', $id)->first();
        return response()->json($this->success($menu));
    }

    /**
     * @OA\Post(
     *     path="/api/menu/update/{id}",
     *     tags={"Menu"},
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
     *                          property="branch_id",
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
     *                     "branch_id":"branch_id",
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
     *              @OA\Property(property="branch_id", type="integr", example="branch_id"),
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
    public function update(Request $request, $id)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $menu = Menu::where('id', $id)->first();
        $input = $request->all();
        if ($request->has('deleteImage') && $input['deleteImage'] == 1) {
            Storage::disk('public_uploads')->delete($menu->image);
            $input['image'] = "";
        }
        if ($request->has('image')) {
            Storage::disk('public_uploads')->delete($menu->image);
            $image_path = $request->file('image')->store('image', 'public_uploads');
            $input['image'] = $image_path;
        }
        $menu->update($input);
        return response()->json($this->success($menu));
    }
    /**
     * @OA\Delete(
     *     path="/api/menu/delete/{id}",
     *     tags={"Menu"},
     *     summary="delete  Menu",
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
        $menu = Menu::where('id', $id)->first();
        Storage::disk('public_uploads')->delete($menu->image);
        $menu->delete();
        return response()->json($this->success());
    }
}
