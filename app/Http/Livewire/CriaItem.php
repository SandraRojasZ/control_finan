<?php
namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;

class CriaItem extends Component
{
    public $data, $name, $valor, $tipo, $saldo;

    public function store()
    {
        $this->validate([
            'data' => 'required|date',
            'name' => 'required|string',
            'valor' => 'required|numeric',
            'tipo' => 'required|in:receita,gasto',
            'saldo' => 'required|numeric',
        ]);

        $item = new Item();
        $item->user_id = auth()->id();
        $item->data = $this->data;
        $item->name = $this->name;
        $item->valor = $this->valor;
        $item->tipo = $this->tipo;
        $item->saldo = $this->saldo;
        $item->save();

        session()->flash('message', 'Item criado com sucesso!');

        $this->resetInput();
    }

    private function resetInput()
    {
        $this->data = '';
        $this->name = '';
        $this->valor = '';
        $this->tipo = '';
        $this->saldo = '';
    }

    public function render()
    {
        return view('livewire.create-item');
    }
}