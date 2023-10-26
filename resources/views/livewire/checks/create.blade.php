<div>
    <div class="flex items-center justify-between mb-4">          
         <x-jet-button wire:click="save">
            Guardar
         </x-jet-button>
    </div>   
        
    {{-- Header check --}}     
    <div class="">      
        @include('livewire.checks.partials.form') 
    </div>

    <div class="hidden sm:block">
        <div class="py-4">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

     {{-- expenses --}}
     <div class="mb-4">   
        @include('livewire.checks.partials.supplier-expenses')
    </div>

    {{-- supplier modal --}}
    @include('livewire.checks.partials.supplier-modal')

    {{-- invoices modal --}}
     @include('livewire.checks.partials.invoices-modal')

    {{-- Voucher conformation modal --}}
    <x-jet-dialog-modal wire:model="confirmingVoucher">
        <x-slot name="title">
            <h2 class="font-bold font-lg">Comprobante de egreso</h2>
        </x-slot>
    
        <x-slot name="content">
            ¿Deseas generar el comprobante de egreso?
        </x-slot>
    
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingVoucher')" wire:loading.attr="disabled">
                Cancelar    
            </x-jet-secondary-button>               

            <a href="{{ route('checks.voucher', $lastid) }}" target="_blank" wire:click="closeModal" class="ml-4 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">Aceptar</a>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        $(document).ready(function(){
            $('#accounts_select').select2({
                placeholder: {        
                id: -1,        
                text: "Selecciona una cuenta"
                }                            
            });         

            $('#accounts_select').on('change', function(e){
                let valor = $('#accounts_select').select2('val');                

                //   Asignamos el valor o el texto a una variable pública del componente livewire
                @this.set('account_id', valor);            
            });
        });   

        // cambiar valor de pago de factura
        function changePayValue(rowId, id) {
            let input = document.getElementById(id+'pay_value');
            let input_value = input.value;

            // Livewire.on('changePayValue', [rowId, value] => {
                
            // })

            Livewire.emit('changePayValue', {
                row:rowId, value:input_value
            });
        }

        //  Captura un mensaje enviado por el componente y lo muestra en pantalla
        window.addEventListener('success', event => {                 
            // Reset select2
            $("#accounts_select").val('').trigger('change');       
        });
    </script>
</div>

