<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class SpaController extends Controller
{
    public function index()
    {
        return view('spa');
    }
}
