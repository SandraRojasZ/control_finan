<div>
    <input type="text" {{ $attributes->merge(['class' => 'flatpickr-input']) }} />
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                flatpickr('.flatpickr-input', {
                    dateFormat: 'd-m-Y', // Formato da data
                });
            });
        </script>
    @endpush
@endonce