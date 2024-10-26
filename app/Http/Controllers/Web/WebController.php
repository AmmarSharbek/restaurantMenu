<?php

namespace App\Http\Controllers\Web;

use App\Enums\ErrorCode;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Domain;
use App\Models\Menu;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\SocialMedia;
use App\Models\Style;
use App\Models\SubOption;
use App\Models\Tenant;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StatisticsVisit;

class WebController extends Controller
{
    use ResponseHandler;
    /**
     * @OA\Post(
     *     path="/api/getBranch",
     *     tags={"Web"},
     *     description="" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="domain",
     *                          type="string",
     *                      )
     *                 ),
     *                 example={
     *                     "domain":"domain"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="domain", type="string", example="domain"),
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
    public function getBranch(Request $request)
    {
        $input = $request->all();
        // $domain = Domain::where('domain', $input['domain'])->first();
        // $tenant = Tenant::where('id', $domain['tenant_id'])->first();
        // tenancy()->initialize($tenant);
        $restaurant = Restaurant::where('domin', $input['domain'])->first();
        if (!$restaurant || $restaurant->isActive == false) {
            return response()->json(['error' => 'Restaurant is not active'], 403);
        }
        $branch = Branch::where('restaurant_id', $restaurant['id'])->get();

        if (!$branch) {
            return response()->json(['error' => 'Branches not found'], 403);
        }

        $style = Style::where('restaurant_id', $restaurant['id'])->first();
        $social = SocialMedia::where('restaurant_id', $restaurant['id'])->get();
        // return $staticV;
        return response()->json($this->success([
            'restaurant' => $restaurant,
            'branch' => $branch,
            'style' => $style,
            'social' => $social,
        ]));
    }
    /**
     * @OA\Post(
     *     path="/api/getMenu",
     *     tags={"Web"},
     *     description="" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="domain",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="branch_id",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="visit",
     *                          type="boolean",
     *                      ),
     *                 ),
     *                 example={
     *                     "domain":"domain",
     *                     "branch_id":"branch_id",
     *                     "visit":"1",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="domain", type="string", example="domain"),
     *              @OA\Property(property="branch_id", type="integr", example="branch_id"),
     *              @OA\Property(property="visit", type="boolean", example="1"),
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
    public function getMenu(Request $request)
    {
        // $arr = ["aa" => "xx", "bb" => "xy"];
        // return array_keys($arr);
        $input = $request->all();

        // $domain = Domain::where('domain', $input['domain'])->first();
        // $tenant = Tenant::where('id', $domain['tenant_id'])->first();
        // tenancy()->initialize($tenant);
        $restaurant = Restaurant::where('domin', $input['domain'])->first();
        if (!$restaurant || $restaurant->isActive == false) {
            return response()->json(['error' => 'Restaurant is not active'], 403);
        }
        $branch = Branch::where('restaurant_id', $restaurant['id'])->where('id', $input['branch_id'])->first();

        if (!$branch) {
            return response()->json(['error' => 'Branch not found'], 403);
        }
        if ($input['visit']) {

            $branch['num_visit'] = $branch['num_visit'] + 1;
            $branch->update();
            // return $branch['num_visit'];
            $arrayOfVisit = [];
            $arrayOfdate = [];
            $arrayOfCity = [];
            $arrayOfCountry = [];
            $arrayOfSystem = [];
            $numVisit =  $branch['num_visit'];
            // return $numVisit;
            $svisit = StatisticsVisit::where('name', "branch")->where('id_of_name', $input['branch_id'])->first();
            if (!$svisit) {
                $input['name'] = "branch";
                $input['id_of_name'] = $input['branch_id'];
                $arrayOfVisit[] = $numVisit;
                $input['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                $arrayOfdate[] = getdate();
                $input['array_of_date_visit'] = json_encode($arrayOfdate);
                $input['sumVisit'] = $numVisit;
                if ($request->has("city")) {
                    $arrayOfCity = collect([["name" => $input['city'], "visit" => 1]]);
                    $input['array_of_city'] = $arrayOfCity;
                    // return $arrayOfCity;
                }
                if ($request->has("country")) {
                    $arrayOfCountry = collect([["name" => $input['country'], "visit" => 1]]);
                    $input['array_of_country'] = $arrayOfCountry;
                    // return $arrayOfCity;
                }
                if ($request->has("system")) {
                    $arrayOfSystem = collect([["name" => $input['system'], "visit" => 1]]);
                    $input['array_of_system'] = $arrayOfSystem;
                    // return $arrayOfCity;
                }
                $svisit = StatisticsVisit::create($input);
            } else {
                if ($request->has("city")) {
                    if ($svisit['array_of_city'] == null) {
                        $arrayOfCity = collect([["name" => $input['city'], "visit" => 1]]);
                        $svisit['array_of_city'] = json_encode($arrayOfCity);
                    } else {
                        $arrayOfCity = json_decode($svisit['array_of_city']);
                        $arrayOfCity = collect($arrayOfCity);
                        if ($arrayOfCity->contains("name", $input['city'])) {
                            for ($i = 0; $i < $arrayOfCity->count(); $i++) {
                                if ($arrayOfCity[$i]->name == $input['city']) {
                                    $arrayOfCity[$i]->name =  $input['city'];
                                    $arrayOfCity[$i]->visit =  $arrayOfCity[$i]->visit + 1;
                                }
                            }
                        } else {
                            $arrayOfCity = $arrayOfCity->push(["name" => $input['city'], "visit" => 1]);
                        }
                        $svisit['array_of_city'] = json_encode($arrayOfCity);
                    }
                }
                if ($request->has("country")) {
                    if ($svisit['array_of_country'] == null) {
                        $arrayOfCountry = collect([["name" => $input['country'], "visit" => 1]]);
                        $svisit['array_of_country'] = json_encode($arrayOfCountry);
                    } else {
                        $arrayOfCountry = json_decode($svisit['array_of_country']);
                        $arrayOfCountry = collect($arrayOfCountry);
                        if ($arrayOfCountry->contains("name", $input['country'])) {
                            for ($i = 0; $i < $arrayOfCountry->count(); $i++) {
                                if ($arrayOfCountry[$i]->name == $input['country']) {
                                    $arrayOfCountry[$i]->name =  $input['country'];
                                    $arrayOfCountry[$i]->visit =  $arrayOfCountry[$i]->visit + 1;
                                }
                            }
                        } else {
                            $arrayOfCountry = $arrayOfCountry->push(["name" => $input['country'], "visit" => 1]);
                        }
                        $svisit['array_of_country'] = json_encode($arrayOfCountry);
                    }
                    // return $svisit['array_of_city'];
                }
                if ($request->has("system")) {
                    if ($svisit['array_of_system'] == null) {
                        $arrayOfSystem = collect([["name" => $input['system'], "visit" => 1]]);
                        $svisit['array_of_system'] = json_encode($arrayOfSystem);
                    } else {
                        $arrayOfSystem = json_decode($svisit['array_of_system']);
                        $arrayOfSystem = collect($arrayOfSystem);
                        if ($arrayOfSystem->contains("name", $input['system'])) {
                            for ($i = 0; $i < $arrayOfSystem->count(); $i++) {
                                if ($arrayOfSystem[$i]->name == $input['system']) {
                                    $arrayOfSystem[$i]->name =  $input['system'];
                                    $arrayOfSystem[$i]->visit =  $arrayOfSystem[$i]->visit + 1;
                                }
                            }
                        } else {
                            $arrayOfSystem = $arrayOfSystem->push(["name" => $input['system'], "visit" => 1]);
                        }
                        $svisit['array_of_system'] = json_encode($arrayOfSystem);
                    }
                    // return $svisit['array_of_city'];
                }
                $arrayOfVisit = json_decode($svisit["array_of_number_of_visit"]);
                $arrayOfdate = json_decode($svisit["array_of_date_visit"]);
                // return $arrayOfdate[0]->year;
                $now = getdate();
                $yearnow = $now["year"];
                $daynow = $now["mday"];
                $mounthnow = $now["mon"];
                $lastindex = sizeof($arrayOfdate) - 1;
                $year = $arrayOfdate[$lastindex]->year;
                $day = $arrayOfdate[$lastindex]->mday;
                $mounth = $arrayOfdate[$lastindex]->mon;
                $arrayOfVisit[$lastindex] = $numVisit;
                $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                $svisit['sumVisit'] = $svisit['sumVisit'] + 1;
                if ($yearnow > $year) {
                    $arrayOfVisit[$lastindex] = $numVisit;
                    $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                    $arrayOfdate[] = getdate();
                    $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                    $branch['num_visit'] = 0;
                    $svisit['sumVisit'] = $svisit['sumVisit'] + $numVisit;
                } else {
                    if ($mounthnow > $mounth) {
                        $arrayOfVisit[$lastindex] = $numVisit;
                        $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                        $arrayOfdate[] = getdate();
                        $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                        $branch['num_visit'] = 0;
                    } else {
                        if ($daynow > $day) {
                            $arrayOfVisit[$lastindex] = $numVisit;
                            $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                            $arrayOfdate[] = getdate();
                            $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                            $branch['num_visit'] = 0;
                        }
                    }
                }
                // if($arrayOfdate[$i][])
                $svisit->update();
            }
            $branch->update();
        }
        $menu = Menu::where('branch_id', $branch['id'])->get();
        $style = Style::where('restaurant_id', $restaurant['id'])->first();
        $social = SocialMedia::where('restaurant_id', $restaurant['id'])->get();
        $arrProduct = [];
        // $staticV = StatisticsVisit::where("name", "product")->orderby("sumVisit", 'DESC')->get();
        // return $svisit;
        // if ($staticV) {

        //     foreach ($staticV as $data) {
        //         $product = Product::where('id', $data['id_of_name'])->first();
        //         $category = Category::where('id', $product['category_id'])->first();
        //         $menu1 = Menu::where('id', $category['menu_id'])->first();
        //         // return $menu1;
        //         if ($menu1['branch_id'] == $branch['id']) {
        //             $arrProduct[] = [
        //                 "product" => $product,
        //                 "sumVisit" => $data['sumVisit'],
        //             ];
        //         }
        //     }
        // }
        // 'productWithVisit' => $arrProduct,
        return response()->json($this->success([
            'restaurant' => $restaurant,
            'branch' => $branch,
            'menu' => $menu,
            'style' => $style,
            'social' => $social,
        ]));
    }
    /**
     * @OA\Post(
     *     path="/api/getCategory",
     *     tags={"Web"},
     *     description="" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="domain",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="branch_id",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="menu_id",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="visit",
     *                          type="boolean",
     *                      ),
     *                 ),
     *                 example={
     *                     "domain":"domain",
     *                     "branch_id":"branch_id",
     *                     "menu_id":"menu_id",
     *                     "visit":"1",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="domain", type="string", example="domain"),
     *              @OA\Property(property="branch_id", type="integr", example="branch_id"),
     *              @OA\Property(property="menu_id", type="integr", example="menu_id"),
     *              @OA\Property(property="visit", type="boolean", example="1"),
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
    public function getCategory(Request $request)
    {
        $input = $request->all();
        $restaurant = Restaurant::where('domin', $input['domain'])->first();
        if (!$restaurant || $restaurant->isActive == false) {
            return response()->json(['error' => 'Restaurant is not active'], 403);
        }
        $menu = Menu::where('id', $input['menu_id'])->first();
        if ($input['visit']) {

            $menu['num_visit'] = $menu['num_visit'] + 1;
            $menu->update();
            // return $branch['num_visit'];
            $arrayOfVisit = [];
            $arrayOfdate = [];
            $numVisit =  $menu['num_visit'];
            // return $numVisit;
            $svisit = StatisticsVisit::where('name', "menu")->where('id_of_name', $input['menu_id'])->first();
            if (!$svisit) {
                $input['name'] = "menu";
                $input['id_of_name'] = $input['menu_id'];
                $arrayOfVisit[] = $numVisit;
                $input['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                $arrayOfdate[] = getdate();
                $input['array_of_date_visit'] = json_encode($arrayOfdate);
                $input['sumVisit'] = $numVisit;
                $svisit = StatisticsVisit::create($input);
            } else {
                $arrayOfVisit = json_decode($svisit["array_of_number_of_visit"]);
                $arrayOfdate = json_decode($svisit["array_of_date_visit"]);
                // return $arrayOfdate[0]->year;
                $now = getdate();
                $yearnow = $now["year"];
                $daynow = $now["mday"];
                $mounthnow = $now["mon"];
                $lastindex = sizeof($arrayOfdate) - 1;
                $year = $arrayOfdate[$lastindex]->year;
                $day = $arrayOfdate[$lastindex]->mday;
                $mounth = $arrayOfdate[$lastindex]->mon;
                $arrayOfVisit[$lastindex] = $numVisit;
                $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                $svisit['sumVisit'] = $svisit['sumVisit'] + 1;
                if ($yearnow > $year) {
                    $arrayOfVisit[$lastindex] = $numVisit;
                    $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                    $arrayOfdate[] = getdate();
                    $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                    $menu['num_visit'] = 0;
                    $svisit['sumVisit'] = $svisit['sumVisit'] + $numVisit;
                } else {
                    if ($mounthnow > $mounth) {
                        $arrayOfVisit[$lastindex] = $numVisit;
                        $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                        $arrayOfdate[] = getdate();
                        $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                        $menu['num_visit'] = 0;
                    } else {
                        if ($daynow > $day) {
                            $arrayOfVisit[$lastindex] = $numVisit;
                            $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                            $arrayOfdate[] = getdate();
                            $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                            $menu['num_visit'] = 0;
                        }
                    }
                }
                // if($arrayOfdate[$i][])
                $svisit->update();
            }
            $menu->update();
        }
        // $domain = Domain::where('domain', $input['domain'])->first();
        // $tenant = Tenant::where('id', $domain['tenant_id'])->first();
        // tenancy()->initialize($tenant);

        $category = Category::where('menu_id', $input['menu_id'])->get();
        return response()->json($this->success($category));
    }
    /**
     * @OA\Post(
     *     path="/api/getProduct",
     *     tags={"Web"},
     *     description="" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="domain",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="branch_id",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="category_id",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="visit",
     *                          type="boolean",
     *                      ),
     *                 ),
     *                 example={
     *                     "domain":"domain",
     *                     "branch_id":"branch_id",
     *                     "category_id":"category_id",
     *                     "visit":"1",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="domain", type="string", example="domain"),
     *              @OA\Property(property="branch_id", type="integr", example="branch_id"),
     *              @OA\Property(property="category_id", type="integr", example="category_id"),
     *              @OA\Property(property="visit", type="boolean", example="1"),
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
    public function getProduct(Request $request)
    {
        $input = $request->all();
        $restaurant = Restaurant::where('domin', $input['domain'])->first();
        if (!$restaurant || $restaurant->isActive == false) {
            return response()->json(['error' => 'Restaurant is not active'], 403);
        }
        $category = Category::where('id', $input['category_id'])->first();
        if ($input['visit']) {

            $category['num_visit'] = $category['num_visit'] + 1;
            $category->update();
            // return $branch['num_visit'];
            $arrayOfVisit = [];
            $arrayOfdate = [];
            $numVisit =  $category['num_visit'];
            // return $numVisit;
            $svisit = StatisticsVisit::where('name', "category")->where('id_of_name', $input['category_id'])->first();
            if (!$svisit) {
                $input['name'] = "category";
                $input['id_of_name'] = $input['category_id'];
                $arrayOfVisit[] = $numVisit;
                $input['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                $arrayOfdate[] = getdate();
                $input['array_of_date_visit'] = json_encode($arrayOfdate);
                $input['sumVisit'] = $numVisit;
                $svisit = StatisticsVisit::create($input);
            } else {
                $arrayOfVisit = json_decode($svisit["array_of_number_of_visit"]);
                $arrayOfdate = json_decode($svisit["array_of_date_visit"]);
                // return $arrayOfdate[0]->year;
                $now = getdate();
                $yearnow = $now["year"];
                $daynow = $now["mday"];
                $mounthnow = $now["mon"];
                $lastindex = sizeof($arrayOfdate) - 1;
                $year = $arrayOfdate[$lastindex]->year;
                $day = $arrayOfdate[$lastindex]->mday;
                $mounth = $arrayOfdate[$lastindex]->mon;
                $arrayOfVisit[$lastindex] = $numVisit;
                $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                $svisit['sumVisit'] = $svisit['sumVisit'] + 1;
                if ($yearnow > $year) {
                    $arrayOfVisit[$lastindex] = $numVisit;
                    $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                    $arrayOfdate[] = getdate();
                    $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                    $category['num_visit'] = 0;
                    $svisit['sumVisit'] = $svisit['sumVisit'] + $numVisit;
                } else {
                    if ($mounthnow > $mounth) {
                        $arrayOfVisit[$lastindex] = $numVisit;
                        $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                        $arrayOfdate[] = getdate();
                        $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                        $category['num_visit'] = 0;
                    } else {
                        if ($daynow > $day) {
                            $arrayOfVisit[$lastindex] = $numVisit;
                            $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                            $arrayOfdate[] = getdate();
                            $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                            $category['num_visit'] = 0;
                        }
                    }
                }
                // if($arrayOfdate[$i][])
                $svisit->update();
            }
            $category->update();
        }
        // $domain = Domain::where('domain', $input['domain'])->first();
        // $tenant = Tenant::where('id', $domain['tenant_id'])->first();
        // tenancy()->initialize($tenant);
        $arr = [];
        $product = Product::where('category_id', $input['category_id'])->orderBy('sortNum')->get();
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
     * @OA\Post(
     *     path="/api/getOption",
     *     tags={"Web"},
     *     description="" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="domain",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="branch_id",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="product_id",
     *                          type="integer",
     *                      ),
     *                 ),
     *                 example={
     *                     "domain":"domain",
     *                     "branch_id":"branch_id",
     *                     "product_id":"product_id",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="domain", type="string", example="domain"),
     *              @OA\Property(property="branch_id", type="integr", example="branch_id"),
     *              @OA\Property(property="product_id", type="integr", example="product_id"),
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
    public function getOption(Request $request)
    {
        $input = $request->all();
        $restaurant = Restaurant::where('domin', $input['domain'])->first();
        if (!$restaurant || $restaurant->isActive == false) {
            return response()->json(['error' => 'Restaurant is not active'], 403);
        }
        // $domain = Domain::where('domain', $input['domain'])->first();
        // $tenant = Tenant::where('id', $domain['tenant_id'])->first();
        // tenancy()->initialize($tenant);
        $arr = [];
        $option = Option::where('product_id', $input['product_id'])->get();
        foreach ($option as $op) {
            $subOption = SubOption::where('option_id', $op['id'])->get();

            $arr[] = [
                'NameOption_en' => $op['name_en'],
                'NameOption_ar' => $op['name_ar'],
                'subOption' => $subOption,
            ];
        }
        return response()->json($this->success($arr));
    }

    /**
     * @OA\Post(
     *     path="/api/getStyle",
     *     tags={"Web"},
     *     description="" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="domain",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "domain":"domain",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="domain", type="string", example="domain"),
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
    public function getStyle(Request $request)
    {
        $input = $request->all();
        // $domain = Domain::where('domain', $input['domain'])->first();
        // $tenant = Tenant::where('id', $domain['tenant_id'])->first();
        // tenancy()->initialize($tenant);
        $restaurant = Restaurant::where('domin', $input['domain'])->first();
        if (!$restaurant || $restaurant->isActive == false) {
            return response()->json(['error' => 'Restaurant is not active'], 403);
        }
        $style = Style::where('restaurant_id', $restaurant['id'])->first();
        return response()->json($this->success([
            'restaurant' => $restaurant,
            'style' => $style,
        ]));
    }
    /**
     * @OA\Post(
     *     path="/api/getSocialMedia",
     *     tags={"Web"},
     *     description="" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="domain",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="branch_id",
     *                          type="integer",
     *                      ),
     *                 ),
     *                 example={
     *                     "domain":"domain",
     *                     "branch_id":"branch_id",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="domain", type="string", example="domain"),
     *              @OA\Property(property="branch_id", type="integr", example="branch_id"),
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
    public function getSocialMedia(Request $request)
    {
        $input = $request->all();
        // $domain = Domain::where('domain', $input['domain'])->first();
        // $tenant = Tenant::where('id', $domain['tenant_id'])->first();
        // tenancy()->initialize($tenant);
        $restaurant = Restaurant::where('domin', $input['domain'])->first();
        if (!$restaurant || $restaurant->isActive == false) {
            return response()->json(['error' => 'Restaurant is not active'], 403);
        }
        $socialMedia = SocialMedia::where('restaurant_id', $restaurant['id'])->get();
        return response()->json($this->success($socialMedia));
    }

    /**
     * @OA\Post(
     *     path="/api/getProductById/{id}&{visit}",
     *     tags={"Web"},
     *     description="" ,
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         in="path",
     *         name="visit",
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
    public function getProductById($id, $visit)
    {
        $product = Product::where('id', $id)->first();
        if ($visit) {

            $product['num_visit'] = $product['num_visit'] + 1;
            $product->update();
            // return $product['num_visit'];
            $arrayOfVisit = [];
            $arrayOfdate = [];
            $numVisit =  $product['num_visit'];
            // return $numVisit;
            $svisit = StatisticsVisit::where('name', "product")->where('id_of_name', $id)->first();
            if (!$svisit) {
                $input['name'] = "product";
                $input['id_of_name'] = $id;
                // return  $input['id_of_name'];
                $arrayOfVisit[] = $numVisit;
                $input['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                $arrayOfdate[] = getdate();
                $input['array_of_date_visit'] = json_encode($arrayOfdate);
                $input['sumVisit'] = $numVisit;
                $svisit = StatisticsVisit::create($input);
            } else {
                $arrayOfVisit = json_decode($svisit["array_of_number_of_visit"]);
                $arrayOfdate = json_decode($svisit["array_of_date_visit"]);
                // return $arrayOfdate[0]->year;
                $now = getdate();
                $yearnow = $now["year"];
                $daynow = $now["mday"];
                $mounthnow = $now["mon"];
                $lastindex = sizeof($arrayOfdate) - 1;
                $year = $arrayOfdate[$lastindex]->year;
                $day = $arrayOfdate[$lastindex]->mday;
                $mounth = $arrayOfdate[$lastindex]->mon;
                $arrayOfVisit[$lastindex] = $numVisit;
                $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                $svisit['sumVisit'] = $svisit['sumVisit'] + 1;
                if ($yearnow > $year) {
                    $arrayOfVisit[$lastindex] = $numVisit;
                    $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                    $arrayOfdate[] = getdate();
                    $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                    $product['num_visit'] = 0;
                } else {
                    if ($mounthnow > $mounth) {
                        $arrayOfVisit[$lastindex] = $numVisit;
                        $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                        $arrayOfdate[] = getdate();
                        $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                        $product['num_visit'] = 0;
                    } else {
                        if ($daynow > $day) {
                            $arrayOfVisit[$lastindex] = $numVisit;
                            $svisit['array_of_number_of_visit'] = json_encode($arrayOfVisit);
                            $arrayOfdate[] = getdate();
                            $svisit['array_of_date_visit'] = json_encode($arrayOfdate);
                            $product['num_visit'] = 0;
                        }
                    }
                }
                // if($arrayOfdate[$i][])
                $svisit->update();
            }
            $product->update();
            // return $svisit;
        }
        $option = Option::where('product_id', $id)->get();
        foreach ($option as $op) {
            $subOption = SubOption::where('option_id', $op['id'])->get();
            $op['subOption'] = $subOption;
        }
        $product['option'] = $option;
        return response()->json($this->success($product));
    }
    /**
     * @OA\Get(
     *     path="/api/getProductCommon/{branch_id}",
     *     tags={"Web"},
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
    public function getProductCommon(Request $request, $branch_id)
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
                $quere1->orWhere('category_id', $data->id)->where('common', true);
            }
        });
        return response()->json($this->success($quere1->get()));
    }
    /**
     * @OA\Get(
     *     path="/api/getProductNew/{branch_id}",
     *     tags={"Web"},
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
    public function getProductNew(Request $request, $branch_id)
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
                $quere1->orWhere('category_id', $data->id)->where('new', true);
            }
        });
        return response()->json($this->success($quere1->get()));
    }
    /**
     * @OA\Get(
     *     path="/api/getProductPriceOffer/{branch_id}",
     *     tags={"Web"},
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
    public function getProductPriceOffer(Request $request, $branch_id)
    {
        $branch = Branch::where('id', $branch_id)->first();
        $menu = Menu::where('branch_id', $branch_id)->get();
        if (sizeof($menu) == 0) {
            return response()->json($this->success($menu));
        }
        $arrOffer = [];
        $arrNew = [];
        $arrCommon = [];
        foreach ($menu as $data) {
            $category = Category::where('menu_id', $data['id'])->get();
            foreach ($category as $data1) {
                $productoffer = Product::where('category_id', $data1->id)->where('price_offer', '>', 0)->get();
                $productnew = Product::where('category_id', $data1->id)->where('new', true)->get();
                $productcommon = Product::where('category_id', $data1->id)->where('common', true)->get();

                $arrOffer = array_merge($arrOffer, $productoffer->toArray());
                $arrNew = array_merge($arrNew, $productnew->toArray());
                $arrCommon = array_merge($arrCommon, $productcommon->toArray());
            }
        }
        // return $arr;
        // $quere = DB::table('categories');
        // $quere->where(function ($quere) use ($menu) {
        //     foreach ($menu as $data) {
        //         $quere->orWhere('menu_id', $data['id']);
        //     }
        // });
        // $category = $quere->get();
        // $quere1 = DB::table('products')->orderBy('sortNum');
        // $quere1->where(function ($quere1) use ($category) {
        //     foreach ($category as $data) {
        //         $quere1->orWhere('category_id', $data->id)->where('price_offer', '>', 0);
        //     }
        // });
        // return response()->json($this->success($quere1->get()));
        return response()->json($this->success([
            'new' => $arrNew,
            'common' => $arrCommon,
            'offer' => $arrOffer,
            'image_new' => $branch->image_new,
            'image_common' => $branch->image_common,
            'image_offer' => $branch->image_offer,
        ]));
    }

    public function storeOrder(Request $request)
    {
        $input = $request->all();
        $note = "";
        if ($request->has("note")) {
            $note = $input['note'];
        }
        $order = Order::create([
            'name' => $input['name'],
            'phone' => $input['phone'],
            'note' => $note,
        ]);
        foreach ($input['items'] as $item) {
            $order_product = OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'amount' => $item['amount'],
                'price' => $item['price'],
            ]);
        }
    }
}
