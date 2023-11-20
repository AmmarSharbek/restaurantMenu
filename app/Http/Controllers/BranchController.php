<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\BranchRequest;
use App\Models\Branch;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    use ResponseHandler;
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/branch",
     *     tags={"Branch"},
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
        $branch = Branch::all();
        return response()->json($this->success($branch));
    }

    /**
     * @OA\Post(
     *     path="/api/branch/store",
     *     tags={"Branch"},
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
     *                          property="name_ar",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="name_en",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="address_ar",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="address_en",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="mobile",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "restaurant_id":"restaurant_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "address_ar":"address_ar",
     *                     "address_en":"address_en",
     *                     "phone":"phone",
     *                     "mobile":"mobile",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="restaurant_id", type="integr", example="restaurant_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="address_ar", type="string", example="address_ar"),
     *              @OA\Property(property="address_en", type="string", example="address_en"),
     *              @OA\Property(property="phone", type="string", example="phone"),
     *              @OA\Property(property="mobile", type="string", example="mobile"),
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
    public function store(BranchRequest $request)
    {
        $branch = Branch::create($request->all());
        return response()->json($this->success($branch));
    }

    /**
     * @OA\Get(
     *     path="/api/branch/show/{id}",
     *     tags={"Branch"},
     *     summary="Get  Branch",
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
        $branch = Branch::where('id', $id)->first();
        return response()->json($this->success($branch));
    }

    /**
     * @OA\Post(
     *     path="/api/branch/update/{id}",
     *     tags={"Branch"},
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
     *                          property="name_ar",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="name_en",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="address_ar",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="address_en",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="mobile",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "restaurant_id":"restaurant_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "address_ar":"address_ar",
     *                     "address_en":"address_en",
     *                     "phone":"phone",
     *                     "mobile":"mobile",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="restaurant_id", type="integr", example="restaurant_id"),
     *              @OA\Property(property="name_ar", type="string", example="name_ar"),
     *              @OA\Property(property="name_en", type="string", example="name_en"),
     *              @OA\Property(property="address_ar", type="string", example="address_ar"),
     *              @OA\Property(property="address_en", type="string", example="address_en"),
     *              @OA\Property(property="phone", type="string", example="phone"),
     *              @OA\Property(property="mobile", type="string", example="mobile"),
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
    public function update(BranchRequest $request, $id)
    {
        $branch = Branch::where('id', $id)->first();
        $input = $request->all();
        $branch->update($input);
        return response()->json($this->success($branch));
    }
    /**
     * @OA\Delete(
     *     path="/api/branch/delete/{id}",
     *     tags={"Branch"},
     *     summary="delete  Branch",
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
        $branch = Branch::where('id', $id)->first();
        $branch->delete();
        return response()->json($this->success());
    }
}
