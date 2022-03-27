<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        return Service::orderBy('id', 'DESC')->with('customers')->get();
    }

    // public function show($slug) {
    //     return Service::with('products')->with('products.user')->where('slug', $slug)->first();
    // }
}
