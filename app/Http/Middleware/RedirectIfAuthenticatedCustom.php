<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            return redirect()->route('infomation');  
        }

        return $next($request);
    }
}
