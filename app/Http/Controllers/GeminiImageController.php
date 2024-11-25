<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;

class GeminiImageController extends Controller
{
    //
    public function create(){
        return view('Dashboard');
    }

    public function store(Request $request, GeminiService $geminiService){
        $url = $request->input('image_url');

        try{
            $analysisResult = $geminiService->analyzeImage( $url);
            $message = $analysisResult;
        }catch(\Exception $e){
            $menssage = "Não consegui analisar a imagem". $e->getMessage();
        }
        return back()->with('message', $menssage);
    }    
}
