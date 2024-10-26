<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use App\Models\StatisticsVisit;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class StatisticsVisitController extends Controller
{
    use ResponseHandler;

    // /**
    //  * @OA\Post(
    //  *     path="/api/showVisit",
    //  *     tags={"Statistics Visit"},
    //  *     description="" ,
    //  *     @OA\RequestBody(
    //  *         @OA\MediaType(
    //  *             mediaType="application/json",
    //  *             @OA\Schema(
    //  *                 @OA\Property(
    //  *                      type="object",
    //  *                      @OA\Property(
    //  *                          property="name",
    //  *                          type="string",
    //  *                      ),
    //  *                      @OA\Property(
    //  *                          property="id",
    //  *                          type="integer",
    //  *                      ),
    //  *                 ),
    //  *                 example={
    //  *                     "name":"product",
    //  *                     "id":"1",
    //  *                }
    //  *             )
    //  *         )
    //  *      ),
    //  *      @OA\Response(
    //  *          response=200,
    //  *          description="success",
    //  *          @OA\JsonContent(
    //  *              @OA\Property(property="name", type="string", example="product"),
    //  *              @OA\Property(property="id", type="integr", example="1"),
    //  *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
    //  *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
    //  *          )
    //  *      ),
    //  *      @OA\Response(
    //  *          response=400,
    //  *          description="invalid",
    //  *          @OA\JsonContent(
    //  *              @OA\Property(property="msg", type="string", example="CredentialsError = 1;Required = 100;MaxLength255 = 101;Unique = 103;"),
    //  *          )
    //  *      )
    //  * )
    //  */
    // public function showVisit(Request $request)
    // {
    //     $input = $request->all();
    //     $svisit = StatisticsVisit::where('name', $input['name'])->where('id_of_name', $input['id'])->first();
    //     $arrVisit = json_decode($svisit["array_of_number_of_visit"]);
    //     $arrdate = json_decode($svisit["array_of_date_visit"]);
    //     $arr = [];
    //     $sumNumVisit = 0;
    //     for ($i = 0; $i < sizeof($arrVisit); $i++) {
    //         $year = $arrdate[$i]->year;
    //         $day = $arrdate[$i]->mday;
    //         $month = $arrdate[$i]->mon;
    //         $arr[] = ['date' => "{$year}-{$month}-{$day}", 'numVisit' => $arrVisit[$i]];
    //         $sumNumVisit = $sumNumVisit + $arrVisit[$i];
    //     }
    //     return response()->json($this->success([
    //         "arrayOfVisit" => $arr,
    //         "sumNumVisit" => $sumNumVisit,
    //     ]));
    // }

    /**
     * @OA\Post(
     *     path="/api/showVisitWithDate",
     *     tags={"Statistics Visit"},
     *     description="" ,
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                      ),
     *                      @OA\Property(
     *                          property="startDate",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="endDate",
     *                          type="string",
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"product",
     *                     "id":"1",
     *                     "startDate":"2024-8-24",
     *                     "endDate":"2024-8-24",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="product"),
     *              @OA\Property(property="id", type="integr", example="1"),
     *              @OA\Property(property="startDate", type="string", example="2024-8-24"),
     *              @OA\Property(property="endDate", type="string", example="2024-8-24"),
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
    public function showVisitWithDate(Request $request)
    {
        $input = $request->all();
        // if ($input['startDate'] != "0") {

        //     $start = Str::of($input['startDate'])->explode('-');
        // }
        // if ($input['endDate'] != "0") {
        //     $end = Str::of($input['endDate'])->explode('-');
        // }
        if ($input['endDate'] != 0) {
            $input['endDate'] = strtotime($input['endDate']);
            $input['endDate'] = date('Y-m-d', $input['endDate']);
        }
        if ($input['startDate'] != 0) {
            $input['startDate'] = strtotime($input['startDate']);
            $input['startDate'] = date('Y-m-d', $input['startDate']);
        }
        $svisit = StatisticsVisit::where('name', $input['name'])->where('id_of_name', $input['id'])->first();
        if (!$svisit) {
            return response()->json($this->success([
                "name" => "",
                "id" => "",
                "arrayOfVisit" => [],
                "sumNumVisit" => 0,
                "name_en" => "",
                "name_ar" => "",
                "prdouctForBranch" => [],
            ]));
        }
        $arrVisit = json_decode($svisit["array_of_number_of_visit"]);
        $arrdate = json_decode($svisit["array_of_date_visit"]);
        $arrCity = [];
        $arrCity1 = [];
        $arrCounty = [];
        $arrCounty1 = [];
        $arrOfCity = [];
        $arrOfCountry = [];
        $arrOfSystem = [];
        $arrOfSystem1 = [];
        $info = [];
        if ($svisit["array_of_city"] != null) {
            $arrCity = json_decode($svisit["array_of_city"]);
            $arrCity = collect($arrCity);
            $arrCity = $arrCity->sortBy('visit', SORT_REGULAR, true);
            $arrCity1 = $arrCity->values()->all();
            $arrOfCity['max_name'] = $arrCity->max()->name;
            $arrOfCity['max_value'] = $arrCity->max()->visit;
            $arrOfCity['min_name'] = $arrCity->min()->name;
            $arrOfCity['min_value'] = $arrCity->min()->visit;
            $arrOfCity['count_city'] = $arrCity->count();
            $arrOfCity['count_visit'] = 0;
            $info['city'] = $arrCity1;
            $info['city_total'] = 0;
            foreach ($arrCity as $data) {
                $arrOfCity['count_visit'] = $arrOfCity['count_visit'] + $data->visit;
                $info['city_total'] = $info['city_total'] + $data->visit;
            }
            // return $arrOfCity;
            // $info['city_total'] = $arrCity1;
        }
        if ($svisit["array_of_country"] != null) {

            $arrCounty = json_decode($svisit["array_of_country"]);
            $arrCounty = collect($arrCounty);
            $arrCounty = $arrCounty->sortBy('visit', SORT_REGULAR, true);
            $arrCounty1 = $arrCounty->values()->all();
            $arrOfCountry['max_name'] = $arrCounty->max()->name;
            $arrOfCountry['max_value'] = $arrCounty->max()->visit;
            $arrOfCountry['min_name'] = $arrCounty->min()->name;
            $arrOfCountry['min_value'] = $arrCounty->min()->visit;
            $arrOfCountry['count_city'] = $arrCounty->count();
            $arrOfCountry['count_visit'] = 0;
            $info['country'] = $arrCounty1;
            $info['country_total'] = 0;
            foreach ($arrCounty as $data) {
                $arrOfCountry['count_visit'] = $arrOfCountry['count_visit'] + $data->visit;
                $info['country_total'] = $info['country_total'] + $data->visit;
            }
        }
        if ($svisit["array_of_system"] != null) {

            $arrOfSystem = json_decode($svisit["array_of_system"]);
            $arrOfSystem = collect($arrOfSystem);
            $arrOfSystem = $arrOfSystem->sortBy('visit', SORT_REGULAR, true);
            $arrOfSystem1 = $arrOfSystem->values()->all();
           // $arrOfSystem['max_name'] = $arrOfSystem->max()->name;
           // $arrOfSystem['max_value'] = $arrOfSystem->max()->visit;
//$arrOfSystem['min_name'] = $arrOfSystem->min()->name;
          //  $arrOfSystem['min_value'] = $arrOfSystem->min()->visit;
           // $arrOfSystem['count_city'] = $arrOfSystem->count();
           // $arrOfSystem['count_visit'] = 0;
            $info['system'] = $arrOfSystem1;
            $info['system_total'] = 0;
            foreach ($arrCounty as $data) {
            //    $arrOfSystem['count_visit'] = $arrOfSystem['count_visit'] + $data->visit;
                $info['system_total'] = $info['system_total'] + $data->visit;
            }
        }
        $arr = [];
        $sumNumVisit = 0;
        for ($i = 0; $i < sizeof($arrVisit); $i++) {
            $year = $arrdate[$i]->year;
            $day = $arrdate[$i]->mday;
            $month = $arrdate[$i]->mon;
            $dateAll = "{$year}-{$month}-{$day}";
            $dateAll = strtotime($dateAll);
            $dateAll = date('Y-m-d', $dateAll);
            // return $dateAll;
            if ($input['endDate'] < $dateAll && $input['endDate'] != 0) {
                break;
            }
            if ($input['startDate'] > $input['endDate'] && $input['endDate'] != 0 && $input['startDate'] != 0) {
                return response()->json(['error' => 'date is wrong'], 403);
            }
            if ($input['startDate'] == $input['endDate'] && $input['startDate'] == $dateAll) {
                $arr[] = ['date' => $dateAll, 'numVisit' => $arrVisit[$i]];
                $sumNumVisit = $sumNumVisit + $arrVisit[$i];
                break;
            }
            if ($input['startDate'] == $dateAll) {
                $arr = [];
                $sumNumVisit = 0;
            }
            if ($input['endDate'] < $dateAll && $input['endDate'] != 0) {
                // return [$input['endDate'] , $dateAll];
                break;
            }
            $arr[] = ['date' => $dateAll, 'numVisit' => $arrVisit[$i]];
            $sumNumVisit = $sumNumVisit + $arrVisit[$i];
            if ($input['startDate'] > $dateAll) {
                // return "qwe";
                $arr = [];
                $sumNumVisit = 0;
            }
            if ($input['endDate'] == $dateAll) {
                break;
            }
        }
        // return ("100">"2");
        $data1 = "";
        if ($input['name'] == "product") {
            $data1 = Product::where('id', $input['id'])->first();
        }
        $arrProduct = [];
        $x = false;
        if ($input['name'] == "branch") {
            $data1 = Branch::where('id', $input['id'])->first();
            $y = 0;
            $staticV = StatisticsVisit::where("name", "product")->get();
            // return $staticV;
            if ($staticV) {
                foreach ($staticV as $data) {
                    $y = $y + 1;
                    $product = Product::where('id', $data['id_of_name'])->first();
                    if ($product) {
                        $category = Category::where('id', $product['category_id'])->first();
                        $menu1 = Menu::where('id', $category['menu_id'])->first();
                        // return $menu1;
                        if ($menu1['branch_id'] == $input['id']) {
                            $req1 = new Request([
                                "name" => "product",
                                "id" => $product['id'],
                                "startDate" => $input['startDate'],
                                "endDate" => $input['endDate']
                            ]);
                            $arrProduct[] = [
                                $this->showVisitWithDate($req1)->original["data"]
                            ];
                        }
                    }
                }
            }
            if ($y == sizeof($staticV)) {
                $x = true;
            }
        }
        if ($input['name'] == "menu") {
            $data1 = Menu::where('id', $input['id'])->first();
            // $y = 0;
            // $staticV = StatisticsVisit::where("name", "product")->get();
            // // return $staticV;
            // foreach ($staticV as $data) {
            //     $y = $y + 1;
            //     $product = Product::where('id', $data['id_of_name'])->first();
            //     $category = Category::where('id', $product['category_id'])->first();
            //     // $menu1 = Menu::where('id', $category['menu_id'])->first();
            //     // return $menu1;
            //     if ($category['menu_id'] == $input['id']) {
            //         $req1 = new Request([
            //             "name" => "product",
            //             "id" => $product['id'],
            //             "startDate" => $input['startDate'],
            //             "endDate" => $input['endDate']
            //         ]);
            //         $arrProduct[] = [
            //             $this->showVisitWithDate($req1)->original["data"]
            //         ];
            //     }
            // }
            // if ($y == sizeof($staticV)) {
            //     $x = true;
            // }
        }
        if ($input['name'] == "category") {
            $data1 = Category::where('id', $input['id'])->first();
            // $y = 0;
            // $staticV = StatisticsVisit::where("name", "product")->get();
            // // return $staticV;
            // foreach ($staticV as $data) {
            //     $y = $y + 1;
            //     $product = Product::where('id', $data['id_of_name'])->first();
            //     // $category = Category::where('id', $product['category_id'])->first();
            //     // $menu1 = Menu::where('id', $category['menu_id'])->first();
            //     // return $menu1;
            //     if ($menu1['category_id'] == $input['id']) {
            //         $req1 = new Request([
            //             "name" => "product",
            //             "id" => $product['id'],
            //             "startDate" => $input['startDate'],
            //             "endDate" => $input['endDate']
            //         ]);
            //         $arrProduct[] = [
            //             $this->showVisitWithDate($req1)->original["data"]
            //         ];
            //     }
            // }
            // if ($y == sizeof($staticV)) {
            //     $x = true;
            // }
        }

        // if($sumNumVisit == 0){
        //     return "";
        // }
        if ($x) {

            $arrProduct = collect($arrProduct);
            $sorted = $arrProduct->sortBy(function (array $product, int $key) {
                return $product[0]['sumNumVisit'];
            }, SORT_REGULAR, true);

            $arrProduct = $sorted->values()->all();
            // return response()->json($this->success(["prdouctForBranch" => $arrProduct]));
        }
        // $arrProduct->values()->all();
        // $arrProduct = $arrProduct->values()->all();
        return response()->json($this->success([
            "name" => $input['name'],
            "id" => $input['id'],
            "info" => $info,
            "arrayOfVisit" => $arr,
            "sumNumVisit" => $sumNumVisit,
            "name_en" => $data1['name_en'],
            "name_ar" => $data1['name_ar'],
            "prdouctForBranch" => $arrProduct,
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StatisticsVisit $statisticsVisit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatisticsVisit $statisticsVisit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StatisticsVisit $statisticsVisit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatisticsVisit $statisticsVisit)
    {
        //
    }
}
