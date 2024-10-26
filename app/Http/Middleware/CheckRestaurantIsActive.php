<?php

namespace App\Http\Middleware;

use App\Http\Controllers\TenancyFunction;
use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckRestaurantIsActive
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $user1 = User::where('id',$user['id'])->first();
        // if($user1){
        //     return $next($request);    
        // }
        // Retrieve the restaurant ID from the request or wherever it's stored
        $tenantFunction = new  TenancyFunction;
        $init = $tenantFunction->initializeTenant($request);
        // tenancy()->initialize($init['tenant']);
        $restaurant = Restaurant::where('id', $init['idRestaurant'])->first();

        if (!$restaurant || !$restaurant->isActive) {
            return response()->json(['error' => 'Restaurant is not active'], 403);
        }

        return $next($request);
    }
}
