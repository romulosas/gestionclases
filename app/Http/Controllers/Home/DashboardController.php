<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use App\Libraries\Utils;

class DashboardController extends AppController
{

    public function __construct(){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.dashboard');
    }

}
