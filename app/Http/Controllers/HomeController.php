<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Region;
use App\Models\Branch;

class HomeController extends Controller
{
    
    public function index(){
        $branches = Branch::with('region:id,name,slug')
                            ->orderBy('order_number', 'asc')
                            ->where('status','active')->get();
        return view('home', compact('branches'));
    }

}
