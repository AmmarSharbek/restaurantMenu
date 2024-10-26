<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TenancyFunction extends Controller
{
    public  function  initializeTenant($request)
    {
        $idRestaurant = "";
        $authorizationHeader = $request->header('Authorization');

        if (preg_match('/Bearer\s+(.+)/', $authorizationHeader, $matches)) {
            $tokenValue = $matches[1]; // This will contain the token value
            // You can now work with the $tokenValue as needed
            $token = PersonalAccessToken::findToken($tokenValue);
            $idRestaurant = $token->name;
        }
        $restaurant = Restaurant::where('id', $idRestaurant)->first();

        return [
            "restaurant" => $restaurant,
            "idRestaurant" => $idRestaurant,
        ];
    }
}
