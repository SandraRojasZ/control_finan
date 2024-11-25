<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Items extends Component
{
    use WithPagination;

    public $active = false; // Estado do checkbox
    public $q = ''; // Valor da busca
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
    public $sugestaoInvestimento = null;


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
    public function search()
    {
        $this->render();
    }
    public function render()
    {
        // Log para depuração
        Log::info('Estado do filtro ativo: ' . ($this->active ? 'Ativo' : 'Inativo'));
        Log::info('Valor da busca (q): ' . $this->q);
        // Montagem da query com filtros
        $items = Item::where('user_id', auth()->user()->id)
        ->when($this->q, function ($query) {
            return $query->where(function ($query) {
                $query->where('name', 'like', '%'.$this->q . '%')
                ->orWhere('valor', 'like', '%'. $this->q . '%');
            });
        })
       // Filtro de "active" baseado no checkbox
        ->when($this->active, function ($query) {
            $query->where('tipo', 'receita'); // Ajustado para filtrar "receitas"
        })
        ->orderBy($this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');

        // Log para depurar a query SQL
        $query = $items->toSql();
        Log::info('Query SQL gerada: ' . $query);

        $items = $items->paginate(10);
        return view('livewire.items', [
            'items' => $items,
            'query' => $query,
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


    public function obterSugestaoInvestimento()
    {
        // Certifique-se de que o saldo foi calculado
        $this->calcularSaldo();

        // Verifique o valor do saldo antes de definir o prompt
        Log::info('Saldo calculado: ' . $this->saldoAtual);

        // Definindo o prompt para a IA sugerir investimentos com base no saldo
        $prompt = "Você é um consultor financeiro especializado em investimentos de baixo risco e estratégias de economia. Sugira investimentos adequados para uma pessoa com um saldo disponível de R$ {$this->saldoAtual},00, levando em consideração que ela tem um perfil conservador.";

        try {
            // Instanciando o cliente OpenAI corretamente
            $client = new \OpenAI\Client(config('openai.api_key')); // Corrigido para instanciar o Client diretamente

            // Realizando a requisição para o modelo OpenAI
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Você é um consultor financeiro que sugere investimentos.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 50,
            ]);

            // Verifique a resposta da IA
            Log::info('Resposta da IA: ' . $response->choices[0]->message->content);

            // Pegando a sugestão de investimento da resposta da IA
            $this->sugestaoInvestimento = trim(str_replace('Sugestão: ', '', $response->choices[0]->message->content));

        } catch (\Exception $e) {
            $this->sugestaoInvestimento = "Erro ao obter sugestão de investimento: " . $e->getMessage();
        }
    }
    public function updatingActive()
    {
        $this->resetPage();
    }

    public function updatingQ()
    {
        $this->resetPage();
    }
    public function sortBy($field)
    {
        if ($field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

    public function confirmItemDeletion(Item $item)
    {
        $item->delete();
    }
    public function confirmItemAdd()
    {
        $this->reset(['data', 'name', 'valor', 'tipo']);
        $this->confirmingItemAdd = true;
    }
    public function saveItem()
    {
        // Ajustar o valor com base no tipo
        //$valorAjustado = $this->tipo === 'gasto' ? -abs($this->valor) : abs($this->valor);

        // Formatar a data para o formato 'Y-m-d'
        $dataFormatada = Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d');
        // Cálculo do saldo com base no tipo
        //$saldo = $this->tipo === 'receita' ? $this->valor : -$this->valor;

        $item = Item::create([
            'user_id' => auth()->id(), // Inserção automática do user_id
            'data' => $dataFormatada,
            'name' => $this->name,
            'valor' => $this->valor,
            'tipo' => $this->tipo,
            //'saldo' => $saldo,
        ]);

        // Atualiza o saldo após criar o item (usando o getSaldoAttribute())
        //$saldo = $item->saldo;

        $this->reset(['data', 'name', 'valor', 'tipo']);
        $this->confirmingItemAdd = false;

        session()->flash('message', 'Item salvo com sucesso!');

        return redirect()->route('items');
    }

}
