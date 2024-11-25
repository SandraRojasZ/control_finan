<div class="p-6 sm:px-20 bg-white border-b border-gray-200">    
    <div class="mt-8 text-2xl">
        Histórico
    </div>
    {{ $query }}
    <div class="">
        <div class="flex justify-between">
            <div class="p-2">
                <input wire:model.debounce.500ms="q" type="search" placeholder="Buscar" class="shadow appearance-nome border rounded w-full py-2 px-3"/>
            </div>
            <div class="mr-2">
                <input type="checkbox" class="mr-2 leading-tight" wire:model="active" /> Receitas
                
            </div>            
        </div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">
                        <div class="flex items-center">ID</div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">Data</div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">Nome</div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">Valor</div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">Tipo</div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">Saldo</div>
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
                        <td class="border px-4 py-2">Edit Delete</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $items->links()}}
    </div>
</div>
