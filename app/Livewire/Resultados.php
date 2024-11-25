<?php

namespace App\Livewire;

use Livewire\Component;

class Resultados extends Component
{
    
        public $allItems;
    
        public function mount($allItems)
        {
            $this->allItems = $allItems;
        }
    
        public function render()
        {
            return view('livewire.resultados');
        }
}
