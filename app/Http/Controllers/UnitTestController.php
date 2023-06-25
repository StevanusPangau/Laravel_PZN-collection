<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function PHPUnit\Framework\assertEquals;

class UnitTestController extends Controller
{
    public function createCollection()
    {
        $collection = collect([1, 2, 3]);
        dd($collection->all());
    }
}
