<div class="bg-gray-50 border-2 p-4">                
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-4">   
       
        <div class="flex items-center space-x-2 md:col-span-2">
            <x-jet-label value="Cuentas:"/>
            <div wire:ignore class="flex-1">
                <select name="" id="accounts_select" class="shadow-md z-10 w-full">
                    <option value="-1"></option>
                    @foreach ($accounts as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>   
        
        <div class="flex items-center space-x-2 ">
            <x-jet-label value="Saldo:"/>
            <x-jet-input type="text" wire:model.defer="saldo" class="text-center w-full md:py-0 rounded border-gray-400 text-gray-700 disabled:bg-gray-200" id="stock" disabled/>
        </div>

        <div class="flex items-center space-x-2">
            <x-jet-label value="Items"/>
            <x-jet-input type="text" value="{{ count(Cart::instance('new')->content()) }}" class="text-center w-full md:py-0 rounded border-gray-400 text-gray-700 disabled:bg-gray-200" disabled/>
        </div>               
               
        <div class="flex items-center space-x-2">
            <x-jet-label value="Monto:"/>
            <x-jet-input type="text" wire:model.defer="amount"  min="1" max="1000" class="text-center w-full md:py-0 rounded border-gray-400 text-gray-700" />
        </div> 

        <div class="">            
            <button wire:click="addAccount" class="w-full bg-orange-600 items-center px-3 py-3 md:py-1 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">                
                <span class="text-gray-100">Añadir</span>                                  
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
                            <th class="thead">Descripción</th>
                            <th class="thead">Anterior</th>
                            <th class="thead">Abono</th>                            
                            <th class="thead">Actual</th>   
                            <th class="thead"></th>
                        </tr>                        
                    </thead>
                    <tbody class="bg-white">               
                        @if (Cart::instance('new')->count())                                                                                
                            @foreach (Cart::instance('new')->content() as $item)
                                <tr>
                                    <td class="row whitespace-nowrap">{{ $item->name }}</td>
                                    <td class="row">$ {{ $item->options->previus }}</td>                                   
                                    <td class="row">$ {{ $item->price }}</td>         
                                    <td class="row">$ {{ $item->options->new }}</td>                           
                                    <td class="row"><button wire:click="unsetAccount('{{ $item->rowId }}')" class="hover:opacity-25 text-orange-600"><i class="fa-solid fa-trash"></i></button></td>
                                </tr>   
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center text-gray-400">No ha seleccionado ninguna cuenta</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center"><x-jet-input-error for="transaction.content" class="mt-2" /></td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total:</td>
                            <td>$ {{ Cart::subtotal() }}</td>
                        </tr>
                    </tfoot>
                </table>                
            </div>
        </div>
    </div>
   
</div>