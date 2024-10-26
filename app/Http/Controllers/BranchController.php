<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\BranchRequest;
use App\Models\Branch;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use App\Models\StatisticsVisit;
use Illuminate\Support\Facades\Storage;

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
        $branch = Branch::all();
        return response()->json($this->success($branch));
    }
    /**
     * @OA\Get(
     *     path="/api/branch/getBranch",
     *     tags={"Branch"},
     *     summary="Get  Branch",
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
    public function getBranch(Request $request)
    {
        $tenantFunction = new  TenancyFunction;
        $init = $tenantFunction->initializeTenant($request);
        // return $init;
        // tenancy()->initialize($init['tenant']);
        $branch = Branch::where('restaurant_id', $init['idRestaurant'])->get();
        return response()->json($this->success($branch));
    }

    /**
     * @OA\Post(
     *     path="/api/branch/store",
     *     tags={"Branch"},
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
     *                      @OA\Property(
     *                          property="QR",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "restaurant_id":"restaurant_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "description_ar":"description_ar",
     *                     "description_en":"description_en",
     *                     "address_ar":"address_ar",
     *                     "address_en":"address_en",
     *                     "phone":"phone",
     *                     "mobile":"mobile",
     *                     "QR":"QR",
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
     *              @OA\Property(property="description_ar", type="string", example="description_ar"),
     *              @OA\Property(property="description_en", type="string", example="description_en"),
     *              @OA\Property(property="address_ar", type="string", example="address_ar"),
     *              @OA\Property(property="address_en", type="string", example="address_en"),
     *              @OA\Property(property="phone", type="string", example="phone"),
     *              @OA\Property(property="mobile", type="string", example="mobile"),
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
    public function store(BranchRequest $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $input = $request->all();
        $branchOld = Branch::where('restaurant_id', $input['restaurant_id'])->latest('id')->first();;
        if ($branchOld) {
            // return $visitOld;
            $input['num'] = $branchOld->num + 1;
        } else {
            $input['num'] = 1;
        }
        $image_path = "";
        if ($request->has('image_offer')) {
            $image_path = $request->file('image_offer')->store('image', 'public_uploads');
            $input['image_offer'] = $image_path;
        }
        if ($request->has('image_common')) {
            $image_path = $request->file('image_common')->store('image', 'public_uploads');
            $input['image_common'] = $image_path;
        }
        if ($request->has('image_new')) {
            $image_path = $request->file('image_new')->store('image', 'public_uploads');
            $input['image_new'] = $image_path;
        }
        $branch = Branch::create($input);
        return response()->json($this->success($branch));
    }

    /**
     * @OA\Get(
     *     path="/api/branch/show/{id}",
     *     tags={"Branch"},
     *     summary="Get  Branch",
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
    public function show($id, $visit, Request $request)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $branch = Branch::where('id', $id)->first();

        return response()->json($this->success($branch));
    }

    /**
     * @OA\Post(
     *     path="/api/branch/update/{id}",
     *     tags={"Branch"},
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
     *                      @OA\Property(
     *                          property="QR",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "restaurant_id":"restaurant_id",
     *                     "name_ar":"name_ar",
     *                     "name_en":"name_en",
     *                     "description_ar":"description_ar",
     *                     "description_en":"description_en",
     *                     "address_ar":"address_ar",
     *                     "address_en":"address_en",
     *                     "phone":"phone",
     *                     "mobile":"mobile",
     *                     "QR":"QR",
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
     *              @OA\Property(property="description_ar", type="string", example="description_ar"),
     *              @OA\Property(property="description_en", type="string", example="description_en"),
     *              @OA\Property(property="address_ar", type="string", example="address_ar"),
     *              @OA\Property(property="address_en", type="string", example="address_en"),
     *              @OA\Property(property="phone", type="string", example="phone"),
     *              @OA\Property(property="mobile", type="string", example="mobile"),
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
    public function update(BranchRequest $request, $id)
    {
        // $tenantFunction = new  TenancyFunction;
        // $init = $tenantFunction->initializeTenant($request);

        // tenancy()->initialize($init['tenant']);
        $branch = Branch::where('id', $id)->first();
        $input = $request->all();
        
       if ($request->has('image_offer')) {
            if ($branch->image_offer != null) {

                Storage::disk('public_uploads')->delete($branch->image_offer);
            }
            $image_path = $request->file('image_offer')->store('image', 'public_uploads');
            $input['image_offer'] = $image_path;
        }
        if ($request->has('image_common')) {
            if ($branch->image_common != null) {

                Storage::disk('public_uploads')->delete($branch->image_common);
            }
            $image_path = $request->file('image_common')->store('image', 'public_uploads');
            $input['image_common'] = $image_path;
        }
        if ($request->has('image_new')) {
            if ($branch->image_new != null) {

                Storage::disk('public_uploads')->delete($branch->image_new);
            }
            $image_path = $request->file('image_new')->store('image', 'public_uploads');
            $input['image_new'] = $image_path;
        }
      if ($request->has('delete_offer')) {
            if ($input['delete_offer']) {+
                Storage::disk('public_uploads')->delete($branch->image_offer);
                $input['image_offer'] = null;
            }
            
        }
        if ($request->has('delete_common')) {
            if ($input['delete_common']) {
                Storage::disk('public_uploads')->delete($branch->image_common);
              $input['image_common'] = null;
            }
             
        }
        if ($request->has('delete_new')) {
            if ($input['delete_new']) {
                Storage::disk('public_uploads')->delete($branch->image_new);
              $input['image_new'] =null;
            }
             
        }
        $branch->update($input);
        return response()->json($this->success($branch));
    }
    /**
     * @OA\Delete(
     *     path="/api/branch/delete/{id}",
     *     tags={"Branch"},
     *     summary="delete  Branch",
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
        $branch = Branch::where('id', $id)->first();
        $branch->delete();
        return response()->json($this->success());
    }
}
