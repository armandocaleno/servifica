<div>
    @if ($errors->any())
        <div class="text-red-800 text-sm mb-4 w-full rounded border border-red-700 bg-rose-100 p-4">
            <ul>
                <li class=" font-bold">La transacción no se guardó porque se detectaron los siguientes errores:</li>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif   
   
    <form action="{{ route('transactions.lotes.store') }}" method="POST" id="partners_list">   
    @csrf 
        <div class="flex items-center justify-between mb-4">          
            <x-jet-button type="submit">
            Guardar
            </x-jet-button>
        </div>
  
        <div class=" grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-4 bg-gray-50 border-2 px-4 pt-2 pb-6">                   
            <div class="">
                <x-jet-label for="date" value="Fecha:" />                
                <input type="date" name="date" class="block sm:py-0 rounded border-gray-400  w-full text-gray-700" wire:model="date">                
                <x-jet-input-error for="date" class="mt-2" />                
            </div>              

            <div class="md:col-span-3">
                <x-jet-label for="reference" value="Referencia:" />                
                <input type="text" name="reference" class="block sm:py-0 rounded border-gray-400  w-full sm:w-44 xl:w-full text-gray-600" value="{{ old('reference') }}" wire:model="reference">                
                <x-jet-input-error for="reference" class="mt-2" />           
            </div>            
        </div>

        {{-- detail --}}
        <div class="p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">               
                <div class="flex items-center space-x-2 md:col-span-2">
                    <x-jet-label value="Cuentas:"/>
                    <div class="flex-1">
                        <select name="account_id" id="accounts_select" class="rounded z-10 w-full py-0" wire:model="account_id">
                            <option value="0"></option>
                            @foreach ($accounts as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <x-jet-input-error for="account_id" class="mt-2" />   
                    </div>
                </div>                                     
                    
                <div class="flex items-center space-x-2">
                    <x-jet-label value="Monto:"/>
                    <x-jet-input type="text" wire:model="amount"  min="1" max="1000" class="text-center w-full md:py-0 rounded border-gray-400 text-gray-700" />
                </div> 

                <div class="">            
                    <button wire:click="partnersLoad" type="button" class="w-full bg-orange-600 items-center px-3 py-3 md:py-1 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25">                
                        <span class="text-gray-100">Cargar socios</span>                                  
                    </button>
                </div>                                                                
            </div>   
            
            {{-- Table --}}       
            <div class="flex flex-col">
                <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">       
                    <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                        <table class="min-w-full">
                            <thead>
                                <tr>                            
                                    <th class="hidden"></th>                                
                                    <th class="thead"><input class="cursor-pointer" type="checkbox" id="select" checked></th>  
                                    <th class="thead">Código</th>
                                    <th class="thead">Nombres</th>
                                    <th class="thead">Apellidos</th>                                                                                                   
                                    <th class="thead">Cuenta</th> 
                                    <th class="thead">Monto</th>                                
                                </tr>                        
                            </thead>
                            <tbody class="bg-white">               
                            @if (count($partners))
                                @foreach ($partners as $key => $item)
                                        <tr>                           
                                            <td class="hidden"><input type="hidden" name="partner_id[]" value="{{ $item->id }}"></td>                                                                              
                                            <td class="text-center"><input type="checkbox" name="selected_partner[]" value="{{ $item->id }}" checked></td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->lastname }}</td>                                                                                      
                                            <td>
                                                @if ($account)
                                                    {{ $account->name }}
                                                @endif                                            
                                            </td>
                                            <td class="text-right"><input type="text" name="amounts[]" value="{{ $amount }}"></td>
                                        </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No se han cargado los socios</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>        
        //  Captura un mensaje enviado por el componente y lo muestra en pantalla
        window.addEventListener('success', event => {                 
            // Reset select2
            $("#accounts_select").val('').trigger('change');      
        });
        let select = true;
        document.addEventListener("DOMContentLoaded", function() {           
            document.getElementById('select').addEventListener('click', function(e) {                                 
                if (this.checked == false) {
                    this.checked = true;
                    select = true;
                } else {
                    this.checked = false;
                    select = false;
                }            
                uncheckAll();
            });
        });             
    
        function uncheckAll() {
            document.querySelectorAll('#partners_list input[type=checkbox]').forEach(function(checkElement) {
                if (select == true) {
                    checkElement.checked = false;                 
                } else {
                    checkElement.checked = true;                  
                }                
            });
        }
    </script>
</div>