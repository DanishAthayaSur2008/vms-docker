<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Setting::current();

        if ($setting && $setting->maintenance_mode && ! Auth::check()) {

            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}