<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use Livewire\WithPagination;
use Carbon\Carbon;
use OpenAI;

class Items extends Component
{
    use WithPagination;

    public $active;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item;
    public $confirmingItemAdd = false;
    // Propriedades individuais
    public $data;
    public $name;
    public $valor;
    public $tipo;
    public $saldoAtual = null;

    public $chartData;

    protected $queryString = [
        'active' => ['except' => false],
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];
    protected $rules = [
        'item.data' => 'required|date',
        'item.name' => 'required|string|min:4',
        'item.valor' => 'required|numeric|between:1,100.00',
        'item.tipo' => 'required|in:receita,gasto',

    ];

    public function render()
    {
        $items = Item::where('user_id', auth()->user()->id)
        ->when($this->q, function ($query) {
            return $query->where(function ($query) {
                $query->where('name', 'like', '%'.$this->q . '%')
                ->orWhere('valor', 'like', '%'. $this->q . '%');
            });
        })
        ->when($this->active, function ($query) {
            return $query->active();
        })
        ->orderBy($this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');


        $query = $items->toSql();
        $items = $items->paginate(10);
        return view('livewire.welcome', [
            'items' => $items,
            'query' => $query,
            'chartData' => $this->getChartDataForChartjs(),        
        ]);
    }
    
    public function calcularSaldo()
    {
        // Calculando o saldo atual, considerando receitas e gastos
        $receitas = Item::where('user_id', auth()->user()->id)
            ->where('tipo', 'receita')
            ->sum('valor');

        $gastos = Item::where('user_id', auth()->user()->id)
            ->where('tipo', 'gasto')
            ->sum('valor');

        // O saldo é a diferença entre receitas e gastos
        $this->saldoAtual = $receitas - $gastos;
    }       

    //Coleta e Agrupamento dos Dados
    public function getChartData()
    {
        $items = Item::where('user_id', auth()->user()->id)
            ->selectRaw('strftime("%Y-%m", data) as month, tipo, SUM(valor) as total')
            ->groupBy('month', 'tipo')
            ->get();

        // Agrupamento por mês e tipo (receita/gasto)
        $chartData = [];
        foreach ($items as $item) {
            $chartData[$item->month][$item->tipo] = $item->total;
        }

        return $chartData;
    }
    // Preparação dos Dados para o Chart.js
    public function getChartDataForChartjs()
    {
        $chartData = $this->getChartData();

        // Formatar os dados para o Chart.js (exemplo de formato para um gráfico de linhas)
        $labels = [];
        $datasets = [
            ['label' => 'Receitas', 'data' => [], 'backgroundColor' => 'rgba(75,192,192,0.2)', 'borderColor' => 'rgba(75,192,192,1)'],
            ['label' => 'Gastos', 'data' => [], 'backgroundColor' => 'rgba(255,99,132,0.2)', 'borderColor' => 'rgba(255,99,132,1)'],
        ];

        foreach ($chartData as $month => $data) {
            $labels[] = $month;
            $datasets[0]['data'][] = $data['receita'] ?? 0;
            $datasets[1]['data'][] = $data['gasto'] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }
    public function mount()
    {
        $this->chartData = $this->getChartDataForChartjs();
    }

}
