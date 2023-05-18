<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnitTestController extends Controller
{
    public function createCollection()
    {
        $collection = collect([1, 2, 3]);
        dd($collection->all());
    }
}
