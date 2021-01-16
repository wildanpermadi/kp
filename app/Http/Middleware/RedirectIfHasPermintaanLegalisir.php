<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfHasPermintaanLegalisir
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->alumni->permintaanLegalisir()->exists() || (auth()->user()->role == 'alumni' && (!auth()->user()->alumni->tgl_lahir || !auth()->user()->alumni->email || !auth()->user()->alumni->sk_kelulusan || !auth()->user()->alumni->tgl_kelulusan))) {
            return redirect()->back();
        }

        return $next($request);
    }
}
