<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $isAdmin = Session::get('is_admin', false);
        return view('dashboard.index', compact('isAdmin'));
    }
}