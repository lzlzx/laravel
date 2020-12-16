<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //后台首页
    function index()
    {
        return view("Admin.index");
    }

    public function welcome()
    {
        return view("Admin.welcome");
    }
}
