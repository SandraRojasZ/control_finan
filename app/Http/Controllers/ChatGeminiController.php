<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatGeminiController extends Controller
{
    public function index(){
        return view('dashboard');
    }
    public function ask(Request $request){
        dd($request->all());
    }
}
