<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tenant\TenantProfileRequest;
use App\Models\Tenant;
use App\Models\TenantProfile;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;

class TenantProfileController extends Controller
{
    use ResponseHandler;
    /**
     * @OA\Get(
     *     path="/api/tenantProfile",
     *     tags={"TenantProfile"},
     *     summary="Get all tenant",
     * security={{"sanctum":{}}},
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
        $tenantProfile = TenantProfile::all();
        // return $this->sendResponse($tenant, "");
        return response()->json($this->success($tenantProfile));
    }

    /**
     * store
     * @OA\Post(
     *     path="/api/tenantProfile/store",
     *     tags={"TenantProfile"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="nameTenant",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "nameTenant":"nameTenant",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="nameTenant", type="string", example="nameTenant"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="CredentialsError = 1;Required = 100;MaxLength255 = 101;"),
     *          )
     *      )
     * )
     */
    public function store(TenantProfileRequest $request)
    {
        $input = $request->all();
        $numTenant = TenantProfile::max("numTenant");
        if ($numTenant == null) {
            $numTenant = 0;
        }
        $numTenant++;
        $tenant = Tenant::create([
            "id" => $numTenant,
        ]);
        $tenantProfile = TenantProfile::create([
            'idTenant' => $tenant['id'],
            'nameTenant' => $input['nameTenant'],
            'numTenant' => $numTenant
        ]);
        return response()->json($this->success([$tenantProfile, $tenant]));
    }


    /**
     * Display the specified resource.
     */
    public function show(TenantProfile $tenantProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TenantProfile $tenantProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TenantProfile $tenantProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TenantProfile $tenantProfile)
    {
        //
    }
}
