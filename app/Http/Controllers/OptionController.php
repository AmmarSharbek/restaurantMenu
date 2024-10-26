<?php

namespace App\Http\Controllers;

use App\Http\Requests\Option\OptionRequest;
use App\Models\Branch;
use App\Models\Menu;
use App\Models\Option;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\SubOption;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    use ResponseHandler;
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/option",
     *     tags={"Option"},
     *     summary="Get all Option",
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
        $option = Option::all();
        return response()->json($this->success($option));
    }

    /**
     * @OA\Get(
     *     path="/api/option/getOption/{product_id}",
     *     tags={"Option"},
     *     summary="Get all Option",
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
    public function getOption(Request $request, $product_id)
    {
        // $input = $request->all();
        // $domain = Domain::where('domain', $input['domain'])->first();
        // $tenant = Tenant::where('id', $domain['tenant_id'])->first();
        // tenancy()->initialize($tenant);
        $arr = [];
        $option = Option::where('product_id', $product_id)->get();
        foreach ($option as $op) {
            $subOption = SubOption::where('option_id', $op['id'])->get();

            $arr[] = [
                'id' => $op['id'],
                'NameOption_en' => $op['name_en'],
                'NameOption_ar' => $op['name_ar'],
                'subOption' => $subOption,
            ];
        }
        return response()->json($this->success($arr));
    }

    /**
     * @OA\Post(
     *     path="/api/option/store",
     *     tags={"Option"},
     *     description="" ,
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="product_id",
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
     *                          property="value",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                           type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "product_id":"product_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "value":"value",
     *                     "price":"price",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="product_id", type="integr", example="product_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="value", type="char", example="value"),
     *              @OA\Property(property="price", type="integer", example="price"),
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
    public function store(OptionRequest $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $option = Option::create($request->all());
        return response()->json($this->success($option));
    }
    /**
     * @OA\Get(
     *     path="/api/option/show/{id}",
     *     tags={"Option"},
     *     summary="Get  Option",
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
        $option = Option::where('id', $id)->first();
        $subOption = SubOption::where('option_id', $option['id'])->get();
        $option['subOption'] = $subOption;
        return response()->json($this->success($option));
    }

    /**
     * @OA\Post(
     *     path="/api/option/update/{id}",
     *     tags={"Option"},
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
     *                          property="product_id",
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
     *                          property="value",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                           type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "product_id":"product_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "value":"value",
     *                     "price":"price",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="product_id", type="integr", example="product_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="value", type="char", example="value"),
     *              @OA\Property(property="price", type="integer", example="price"),
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
    public function update(OptionRequest $request, $id)
    { // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $option = Option::where('id', $id)->first();
        $input = $request->all();
        $option->update($input);
        return response()->json($this->success($option));
    }
    /**
     * @OA\Delete(
     *     path="/api/option/delete/{id}",
     *     tags={"Option"},
     *     summary="delete  Option",
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
        $option = Option::where('id', $id)->first();

        // $x = 1;
        while (SubOption::where('option_id', $id)->first()) {
            $subOption = SubOption::where('option_id', $id)->first();
            $subOption->delete();
            // $x = $x + 1;
        }
        // return $x;
        $option->delete();
        return response()->json($this->success());
    }
}
