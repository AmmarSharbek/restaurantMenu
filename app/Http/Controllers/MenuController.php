<?php

namespace App\Http\Controllers;

use App\Http\Requests\Menu\MenuRequest;
use App\Models\Menu;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;

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
        $menu = Menu::all();
        return response()->json($this->success($menu));
    }

    /**
     * @OA\Post(
     *     path="/api/menu/store",
     *     tags={"Menu"},
     *     description="" ,
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
     *                          property="QR",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "branch_id":"branch_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "QR":"QR",
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
     *              @OA\Property(property="QR", type="string", example="QR"),
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
        $menu = Menu::create($request->all());
        return response()->json($this->success($menu));
    }

    /**
     * @OA\Get(
     *     path="/api/menu/show/{id}",
     *     tags={"Menu"},
     *     summary="Get  Menu",
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
        $menu = Menu::where('id', $id)->first();
        return response()->json($this->success($menu));
    }

    /**
     * @OA\Post(
     *     path="/api/menu/update/{id}",
     *     tags={"Menu"},
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
     *                          property="QR",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "branch_id":"branch_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "QR":"QR",
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
     *              @OA\Property(property="QR", type="string", example="QR"),
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
    public function update(MenuRequest $request, $id)
    {
        $menu = Menu::where('id', $id)->first();
        $input = $request->all();
        $menu->update($input);
        return response()->json($this->success($menu));
    }
    /**
     * @OA\Delete(
     *     path="/api/menu/delete/{id}",
     *     tags={"Menu"},
     *     summary="delete  Menu",
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
        $menu = Menu::where('id', $id)->first();
        $menu->delete();
        return response()->json($this->success());
    }
}
