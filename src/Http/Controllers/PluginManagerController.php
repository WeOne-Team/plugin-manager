<?php

namespace Weoneteam\PluginManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PluginManagerController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        // echo "ssssssssssssssss";
        return view('plugin-manager::index');
    }
}
