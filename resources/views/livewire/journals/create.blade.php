<div>
    <div class="flex items-center justify-between mb-4">
        <x-jet-button wire:click="save">
            Guardar
        </x-jet-button>
    </div>

    {{-- Header journal --}}
    <div class="mb-4">
        @include('livewire.journals.partials.header')
    </div>

    {{-- Detail journal --}}
    <div class="mb-4">
        @include('livewire.journals.partials.detail')
    </div>

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
                @this.set('accounting_id', valor);            
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
