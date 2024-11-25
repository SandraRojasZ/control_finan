<?php

namespace App\Http\Controllers;

use App\Livewire\Items;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ResultadosController extends Controller
{
    // Controller
    public function resultados()
    {
        // Obter dados do banco
        $allItems = Item::all();

        // Calcular o saldo total e comparar com a meta (definida pelo usuário)
        $saldo_total = $allItems->sum('valor');
        $meta = 1000; // Exemplo de meta
        $diferenca = $meta - $saldo_total;

        // Preparar os dados para o gráfico (utilizando Chart.js por exemplo)
        $labels = $allItems->pluck('tipo');
        $values = $allItems->pluck('valor');

        // Chamar a API do Gemini com o prompt e o contexto
        $prompt = "O saldo total é de $saldo_total. A meta é de $meta. O usuário está próximo da meta?";
        $response = Http::post('https://api.gemini.com/v1/models/your_model', [
            'prompt' => $prompt,
            // ... outros parâmetros
        ]);

        // Exibir a view com os dados do gráfico e a resposta da IA
        return view('resultados', ['allItems' => $allItems]);
    }
    
}


