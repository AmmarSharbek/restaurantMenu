<?php

namespace App\Http\Controllers;

use App\Http\Requests\Option\SubOptionRequest;
use App\Models\SubOption;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;

class SubOptionController extends Controller
{
    use ResponseHandler;
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/subOption",
     *     tags={"SubOption"},
     *     summary="Get all SubOption",
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
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $subOption = SubOption::all();
        return response()->json($this->success($subOption));
    }

    /**
     * @OA\Post(
     *     path="/api/subOption/store",
     *     tags={"SubOption"},
     *     description="" ,
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="option_id",
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
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="double",
     *                      ),
     *                 ),
     *                 example={
     *                     "option_id":"option_id",
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
     *              @OA\Property(property="option_id", type="integr", example="option_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="value", type="string", example="value"),
     *              @OA\Property(property="price", type="double", example="price"),
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
    public function store(SubOptionRequest $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $subOption = SubOption::create($request->all());
        return response()->json($this->success($subOption));
    }

    /**
     * @OA\Get(
     *     path="/api/subOption/show/{id}",
     *     tags={"SubOption"},
     *     summary="Get  SubOption",
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
        $subOption = SubOption::where('id', $id)->first();
        return response()->json($this->success($subOption));
    }

    /**
     * @OA\Post(
     *     path="/api/subOption/update/{id}",
     *     tags={"SubOption"},
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
     *                          property="option_id",
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
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="double",
     *                      ),
     *                 ),
     *                 example={
     *                     "option_id":"option_id",
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
     *              @OA\Property(property="option_id", type="integr", example="option_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="value", type="string", example="value"),
     *              @OA\Property(property="price", type="double", example="price"),
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
    public function update(SubOptionRequest $request, $id)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $subOption = SubOption::where('id', $id)->first();
        $input = $request->all();
        $subOption->update($input);
        return response()->json($this->success($subOption));
    }
    /**
     * @OA\Delete(
     *     path="/api/subOption/delete/{id}",
     *     tags={"SubOption"},
     *     summary="delete  SubOption",
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
        $subOption = SubOption::where('id', $id)->first();
        $subOption->delete();
        return response()->json($this->success());
    }
}
