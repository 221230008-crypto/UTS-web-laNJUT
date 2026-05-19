<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        // Password default: damkar2024
        if ($request->password === 'damkar2024') {
            Session::put('is_admin', true);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Password salah!']);
    }

    public function logout()
    {
        Session::forget('is_admin');
        return response()->json(['success' => true]);
    }

    public function check()
    {
        return response()->json(['is_admin' => Session::get('is_admin', false)]);
    }
}