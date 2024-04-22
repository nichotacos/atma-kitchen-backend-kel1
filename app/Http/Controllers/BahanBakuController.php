<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index(){

        $data = BahanBaku::get();

        return view('databahanbaku',compact('data'));
    }
}
