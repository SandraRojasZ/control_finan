<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;


class FinancialConsultant extends Component
{
    public $currentStep = 0; // Etapa atual do questionário
    public $answers = []; // Armazena as respostas do usuário
    public $saldoAtual = 0; // Inicializa o saldo com 0
    public $solicitouSaldo = false; // Controle para saber se o saldo foi solicitado

    // Função para calcular o saldo
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

    // Método que é chamado ao iniciar o componente
    public function mount()
    {
        // Chama a função de cálculo de saldo quando o componente for montado
        $this->calcularSaldo();
    }

    // Método chamado quando o usuário avança para a próxima etapa
    public function nextStep($response)
    {
        // Se a primeira interação foi a solicitação do saldo, marcamos que o saldo foi solicitado
        if (strpos(strtolower($response), 'saldo') !== false) {
            $this->solicitouSaldo = true;
            $this->currentStep = 0; // Garantir que estamos no passo inicial após solicitar saldo
            return; // Não avança para a próxima etapa
        }

        // Salvar a resposta na etapa atual
        $this->answers[$this->currentStep] = $response;

        // Avançar para a próxima etapa, garantindo que o índice não ultrapasse o número total de perguntas
        if ($this->currentStep < count($this->questions()) - 1) {
            $this->currentStep++;
        } else {
            $this->finalize();
        }
    }

    // Função que retorna as perguntas
    public function questions()
    {
        // Se o saldo foi solicitado, retornamos apenas a resposta do saldo
        if ($this->solicitouSaldo) {
            return [
                'Oi, tudo bem? O seu saldo atual é de R$ ' . number_format($this->saldoAtual, 2, ',', '.') . '. Se você quiser mais detalhes sobre investimentos, posso te ajudar com algumas perguntas.',
            ];
        }

        return [
            'Oi, tudo bem? Para informações personalizadas, preciso de algumas informações:',
            '',
            
            'Agradeço pelas respostas, elas vão me ajudar a te oferecer as melhores opções!'
        ];
    }

    // Método chamado quando o questionário é finalizado
    //public function finalize()
   // {
        // Processar as respostas e gerar sugestões
       // $this->dispatchBrowserEvent('suggestionsReady', [
            //'answers' => $this->answers,
            //'saldoAtual' => $this->saldoAtual,
        //]);
   // }

    // Método para renderizar a view
    public function render()
    {
        return view('livewire.financial-consultant', [
            'question' => $this->questions()[0] ?? null,
        ]);
    }
}
