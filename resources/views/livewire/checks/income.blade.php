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

    {{-- Detail check --}}
    <div class="mb-4">   
        @include('livewire.checks.partials.detail')
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
                @this.set('account_id', valor);            
            });

        });    

        //  Captura un mensaje enviado por el componente y lo muestra en pantalla
        window.addEventListener('success', event => {                 
            // Reset select2
            $("#accounts_select").val('').trigger('change');       
        });

        function valideKey(evt){
    
            // code is the decimal ASCII representation of the pressed key.
            var code = (evt.which) ? evt.which : evt.keyCode;
            
            if(code==8) { // backspace.
            return true;
            } else if(code>=48 && code<=57) { // is a number.
            return true;
            } else{ // other keys.
            return false;
            }
        }
    </script>
</div>

