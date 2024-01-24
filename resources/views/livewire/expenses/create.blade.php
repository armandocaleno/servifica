<div>
    <div class="flex items-center justify-between mb-4">
        <x-jet-button wire:click="save">
            Guardar
        </x-jet-button>
    </div>

    {{-- Header expense --}}
    <div class="mb-4">
        @include('livewire.expenses.partials.form')
    </div>

    {{-- Detail expense --}}
    <div class="mb-4">
        @include('livewire.expenses.partials.detail')
    </div>

    @push('js')
        <script src="{{ asset('inputmask/dist/jquery.inputmask.js') }}"></script>
        <script>
            $(document).ready(function() {

                var selector = document.getElementById("expense_number");
                var im = new Inputmask("999-999-999999999");
                im.mask(selector);

                var number_value = document.getElementById("expense_number_value");

                $('#expense_number').on('change', function(e) {
                    if ($(selector).inputmask("isComplete")) {

                        let value = $(selector).inputmask('unmaskedvalue');

                        @this.set('expense.number', value);

                    } else {
                        alert('Formato de número de factura incompleto.');

                        number_value.value = '';
                        $('#expense_number').focus();
                    }

                });

                $('#accounts_select').select2({
                    placeholder: {
                        id: -1,
                        text: "Selecciona una cuenta"
                    }
                });

                $('#accounts_select').on('change', function(e) {
                    let valor = $('#accounts_select').select2('val');

                    //   Asignamos el valor o el texto a una variable pública del componente livewire
                    @this.set('account_id', valor);
                });

            });

            //  Captura un mensaje enviado por el componente y lo muestra en pantalla
            window.addEventListener('success', event => {
                // Reset select2
                $("#accounts_select").val('').trigger('change');
            });
        </script>
    @endpush
</div>
