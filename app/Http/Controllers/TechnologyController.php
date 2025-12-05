<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    public function index()
    {
        $technologies = Technology::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return response()->json($technologies);
    }
}
