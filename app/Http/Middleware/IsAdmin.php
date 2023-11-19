<?php

namespace App\Http\Middleware;

use App\Enums\ErrorCode;
use App\Providers\RouteServiceProvider;
use App\Traits\ResponseHandler;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    use ResponseHandler;

    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::user()->isAdmin >= 1) {
            return $next($request);
        }
        return response()->json($this->error(new ErrorCode(ErrorCode::PermissionsError)), 403);
    }
}
