<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $series = Series::all();

        return view('dashboard', ['series' => $series]);
    }
}
