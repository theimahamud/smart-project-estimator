<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($regions);
    }
}
