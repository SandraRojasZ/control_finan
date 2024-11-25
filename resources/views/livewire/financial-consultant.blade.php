<div>
    @if ($question)
        <p>{{ $question }}</p>
        <input type="text" wire:model.defer="response" wire:keydown.enter="nextStep(response)" 
               class="shadow appearance-nome border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:bg-gray-200" />
    @else
        <p>Processando suas respostas...</p>
        <p><strong>Saldo Atual:</strong> R$ {{ number_format($saldoAtual, 2, ',', '.') }}</p> <!-- Exibe o saldo calculado -->
    @endif
</div>

