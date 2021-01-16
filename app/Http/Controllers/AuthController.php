<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getLogin()
    {
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect('/');
        }

        return redirect()->back()->with('error', 'Username atau password salah');
    }

    public function doLogout()
    {
        auth()->logout();

        return redirect('/login');
    }
}
