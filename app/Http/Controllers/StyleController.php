<?php

namespace App\Http\Controllers;

use App\Enums\ErrorCode;
use App\Http\Requests\Style\StyleRequest;
use App\Models\Restaurant;
use App\Models\Style;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use App\Models\StatisticsVisit;

class StyleController extends Controller
{
    use ResponseHandler;
    // /**
    //  * @OA\Get(
    //  *     path="/api/getStyle",
    //  *     summary="Get Style by Domain",
    //  *     tags={"Style"},
    //  *     security={{"bearerAuth":{}}},
    //  *     @OA\Parameter(
    //  *         name="domain",
    //  *         in="query",
    //  *         required=true,
    //  *         description="Domain of the restaurant",
    //  *         example="example.com",
    //  *         @OA\Schema(type="string")
    //  *     ),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Successful operation",
    //  *         @OA\JsonContent(
    //  *             type="object",
    //  *             @OA\Property(property="data", ref=""),
    //  *             @OA\Property(property="message", type="string", example="Style retrieved successfully"),
    //  *         ),
    //  *     ),
    //  *     @OA\Response(
    //  *         response=404,
    //  *         description="Style not found",
    //  *         @OA\JsonContent(
    //  *             type="object",
    //  *             @OA\Property(property="message", type="string", example="Style not found for the provided domain"),
    //  *         ),
    //  *     ),
    //  * )
    //  */
    // public function getStyle(Request $request)
    // {
    //     $input = $request->all();
    //     $restaurant = Restaurant::where('domin', $input['domain'])->first();

    //     if (!$restaurant) {
    //         return response()->json($this->error(new ErrorCode(ErrorCode::NotFound)), 404);
    //     }

    //     $style = Style::where('restaurant_id', $restaurant->id)->first();

    //     if (!$style) {
    //         return response()->json($this->error(new ErrorCode(ErrorCode::NotFound)), 404);
    //     }

    //     return response()->json($this->success($style));
    // }
    /**
     * @OA\Get(
     *     path="/api/style",
     *     tags={"Style"},
     *     summary="Get all Branch",
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
        $statv = StatisticsVisit::all();
       
        $style = Style::all();
        return response()->json($this->success($style));
    }
    /**
     * @OA\Get(
     *     path="/api/style/getStyle",
     *     tags={"Style"},
     *     summary="Get  Style",
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
    public function getStyle(Request $request)
    {
        $tenantFunction = new  TenancyFunction;
        $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $style = Style::where('restaurant_id', $init['idRestaurant'])->get();
        return response()->json($this->success($style));
    }

    /**
     * @OA\Post(
     *     path="/api/style/store",
     *     tags={"Style"},
     *     description="" ,
     *     security={{"sanctum":{}}},
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
     *                          property="primary",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="onPrimary",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="secondary",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="onSecondary",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="enable",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="disable",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="background",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="onBackground",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "restaurant_id":"restaurant_id",
     *                     "primary":"primary",
     *                     "onPrimary":"onPrimary",
     *                     "secondary":"secondary",
     *                     "onSecondary":"onSecondary",
     *                     "enable":"enable",
     *                     "disable":"disable",
     *                     "background":"background",
     *                     "onBackground":"onBackground",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="restaurant_id", type="integr", example="restaurant_id"),
     *              @OA\Property(property="primary", type="string", example="primary"),
     *              @OA\Property(property="onPrimary", type="string", example="onPrimary"),
     *              @OA\Property(property="secondary", type="string", example="secondary"),
     *              @OA\Property(property="onSecondary", type="string", example="onSecondary"),
     *              @OA\Property(property="enable", type="string", example="enable"),
     *              @OA\Property(property="disable", type="string", example="disable"),
     *              @OA\Property(property="background", type="string", example="background"),
     *              @OA\Property(property="onBackground", type="string", example="onBackground"),
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
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $style = Style::create($request->all());
        return response()->json($this->success($style));
    }

    /**
     * @OA\Get(
     *     path="/api/style/show/{id}",
     *     tags={"Style"},
     *     summary="Get  Style",
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
        $style = Style::where('id', $id)->first();
        return response()->json($this->success($style));
    }

    /**
     * @OA\Post(
     *     path="/api/style/update/{id}",
     *     tags={"Style"},
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
     *                          property="primary",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="onPrimary",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="secondary",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="onSecondary",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="enable",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="disable",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="background",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="onBackground",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "restaurant_id":"restaurant_id",
     *                     "primary":"primary",
     *                     "onPrimary":"onPrimary",
     *                     "secondary":"secondary",
     *                     "onSecondary":"onSecondary",
     *                     "enable":"enable",
     *                     "disable":"disable",
     *                     "background":"background",
     *                     "onBackground":"onBackground",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="restaurant_id", type="integr", example="restaurant_id"),
     *              @OA\Property(property="primary", type="string", example="primary"),
     *              @OA\Property(property="onPrimary", type="string", example="onPrimary"),
     *              @OA\Property(property="secondary", type="string", example="secondary"),
     *              @OA\Property(property="onSecondary", type="string", example="onSecondary"),
     *              @OA\Property(property="enable", type="string", example="enable"),
     *              @OA\Property(property="disable", type="string", example="disable"),
     *              @OA\Property(property="background", type="string", example="background"),
     *              @OA\Property(property="onBackground", type="string", example="onBackground"),
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
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
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
        $style = Style::where('id', $id)->first();
        $style->delete();
        return response()->json($this->success());
    }
}
