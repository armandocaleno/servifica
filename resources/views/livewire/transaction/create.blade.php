<div>
    <div class="flex items-center justify-between mb-4">          
         <x-jet-button wire:click="save" wire:loading.attr="disabled">
            Guardar
         </x-jet-button>
    </div>   
        
    {{-- Header transaction --}}     
    <div class="mb-4">      
        @include('livewire.transaction.partials.header') 
    </div>

    {{-- Detail transaction --}}
    <div class="mb-4">   
        @include('livewire.transaction.partials.detail')
    </div>

    {{-- Voucher conformation modal --}}
    <x-jet-dialog-modal wire:model="confirmingVoucher">
        <x-slot name="title">
            <h2 class="font-bold font-lg">Recibo de recaudación</h2>
        </x-slot>
    
        <x-slot name="content">
            ¿Deseas generar el recibo de la transacción?.
        </x-slot>
    
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingVoucher')" wire:loading.attr="disabled">
                Cancelar    
            </x-jet-secondary-button>               

            <a href="{{ route('transaction.voucher', $lastid) }}" target="_blank" wire:click="closeModal" class="ml-4 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">Aceptar</a>
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

            $('#partner_select').select2({
                placeholder: {        
                id: -1,        
                text: "Selecciona un socio"
                }                            
            });         

            $('#partner_select').on('change', function(e){
                let valor = $('#partner_select').select2('val');                

                //   Asignamos el valor o el texto a una variable pública del componente livewire
                @this.set('transaction.partner_id', valor);            
            });
        });    

        //  Captura un mensaje enviado por el componente y lo muestra en pantalla
        window.addEventListener('success', event => {                 
            // Reset select2
            $("#accounts_select").val('').trigger('change');      
            $("#partner_select").val('').trigger('change');    
        });
    </script>
</div>
