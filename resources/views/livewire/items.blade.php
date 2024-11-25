<div class="p-6 sm:px-20 bg-white border-b border-gray-200">    
    <div class="flex justify-between items-center mt-8 text-2xl">
       
        <!--Botão Sugestão-->
        <div>
            <x-button wire:click="obterSugestaoInvestimento" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-md transform hover:scale-105 transition duration-300">
            <i class="fas fa-robot smiling"></i>Obter Sugestão de Investimento</x-button>
            @if($sugestaoInvestimento)
                <p> {{ $sugestaoInvestimento }}</p>
            @endif
        </div>
</div>
    <!--Botão Saldo-->
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">    
        <div class="flex justify-between items-center mt-8 text-2xl">            
            <div>
            <!--
            <x-button wire:click="calcularSaldo">{{ __('Calcular Saldo') }}</x-button>-->
            @if($saldoAtual !== null)
                <!--<p>Saldo Atual: R$ {{ number_format($saldoAtual, 2, ',', '.') }}</p>-->
            @endif
        </div>
    </div>

    </div>
    
    <div class="">
        <!--Botão Adicionar-->
        <div class="">
            <x-button wire:click="confirmItemAdd">
            {{ __('Adicionar') }}
            </x-button>
        </div>
        <div class="flex justify-between">
            <!--Campo Busca-->
            <div class="">
                <x-input wire:model.debounce.500ms="q"
                    wire:keydown.enter="search" 
                    type="search" 
                    placeholder="Buscar" 
                    class="shadow appearance-nome border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:bg-gray-200"/>
            </div>
            <!--Filtro Receita-->
            <div class="mr-2">
            <x-input type="checkbox" 
            class="mr-2 leading-tight" 
            wire:model="active" 
            wire:click="$refresh" /> {{ __('Receitas') }}                
            </div>
            <!--Print para teste
            <p>Estado do checkbox: {{ $active ? 'Ativo' : 'Inativo' }}</p>-->       
        </div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button wire:click="sortBy('id')">ID</button>
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button wire:click="sortBy('data')">Data</button>
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button wire:click="sortBy('name')">Categoria</button>
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button wire:click="sortBy('valor')">Valor</button>
                        </div>
                    </th>
                   
                        <th class="px-4 py-2">                        
                            <div class="flex items-center">
                                <button wire:click="sortBy('tipo')">Tipo</button>
                            </div>
                        </th>
                 
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                        <button wire:click="sortBy('saldo')">Saldo</button>
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">Ação</div>
                    </th>                   
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                    <td class="border px-4 py-2">{{ $item->id }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($item->data)->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">{{ $item->name }}</td>
                        <td class="border px-4 py-2">{{ number_format($item->valor, 2, ',', '.') }}</td>
                        
                        <td class="border px-4 py-2">{{ $item->tipo }}</td>
                      
                        <td class="border px-4 py-2">{{ number_format($item->saldo, 2, ',', '.') }}</td>
                        <td class="border px-4 py-2"><!--Editar-->
                            
                            <x-danger-button wire:click="confirmItemDeletion({{ $item->id }})" wire:loading.attr="disabled">
                                {{ __('Deletar') }}
                            </x-danger-button>
                             
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $items->links()}}
    </div>

    <x-dialog-modal wire:model="confirmingItemAdd">
        <x-slot name="title">
            {{'Adicionar Item'}}
        </x-slot>
        <x-slot name="content">
            <!--Data-->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="data" value="{{ __('Data') }}" />
                <x-date-picker wire:model="data" id="data" placeholder="{{ __('dd/mm/aaaa') }}" />
                <x-input-error for="data" class="mt-2" />
            </div>
            <!--Nome-->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('Nome') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="{{ __('Nome do Item')}}"  />
                <x-input-error for="name" class="mt-2" />
            </div>
            <!--Valor-->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="valor" value="{{ __('Valor') }}" />
                <x-input id="valor" type="number" class="mt-1 block w-full" wire:model="valor" />
                <x-input-error for="valor" class="mt-2" />
            </div>
            <!--Tipo-->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="tipo" value="{{ __('Tipo') }}" />
                <x-input id="tipo" type="text" class="mt-1 block w-full" wire:model="tipo" placeholder="{{ __('receita ou gasto') }}" />
                <x-input-error for="tipo" class="mt-2" />
            </div>
                        
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between">
                <x-secondary-button wire:click="$set('confirmingItemAdd', false)" wire:loading.attr="disabled">
                    {{ __('Retornar') }}
                </x-secondary-button>

                <x-danger-button wire:click.prevent="saveItem" wire:loading.attr="disabled">
                    {{ __('Salvar') }}
                </x-danger-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

