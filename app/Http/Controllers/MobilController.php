<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index()
    {
        return view('mobil.index');
    }
}
