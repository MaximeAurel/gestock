<?php

namespace App\Http\Controllers;



class DashboardController extends Controller
{
    // Afficher le dashboard
    public function index()
    {
        return view('dashboard');
    }
}