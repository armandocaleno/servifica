<div>    

    <div class=" bg-gray-50 border-2 px-4 pt-2 pb-6 mb-4">
        <form action="{{ route('reporting.transactions.generate') }}" method="post" target="_blank">
        @csrf
        
            {{-- filters --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4">           
                {{-- from --}}
                <div>
                    <x-jet-label value="Desde" />                
                    <input type="date" name="from" class="block py-0 px-4 rounded border-gray-400 w-full text-gray-700" wire:model="from_date" >
                </div>
                
                {{-- to --}}
                <div>
                    <x-jet-label value="Hasta" />                
                    <input type="date" name="to" class="block py-0 rounded border-gray-400 w-full text-gray-700" wire:model="to_date" >
                </div>

                {{-- types --}}
                <div class="">
                    <x-jet-label for="type_id" value="Tipo"/>
                    <div>
                        <select name="type_id" class="py-0 rounded border-gray-400 w-full text-gray-600" wire:model="type">
                            <option value="0">Todas</option>
                            <option value="1">Individual</option>
                            <option value="2">Lotes</option>
                            <option value="3">Pago</option>
                        </select> 
                    </div>                                                    
                </div> 

                {{-- Partners --}}
                <div class="">
                    <x-jet-label for="partner" value="Socio"/>
                    <div wire:ignore>
                        <select name="partner_id" class="py-0 rounded border-gray-400 w-full text-gray-600" id="partner_select">
                            <option value="">Todos</option>
                            @foreach ($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select> 
                    </div>                                                    
                </div>   

                {{-- accounts --}}
                <div class="">
                    <x-jet-label for="account" value="Rubros"/>
                    <div>
                        <select name="account_id" class="py-0 rounded border-gray-400 w-full text-gray-600" id="account_select" wire:model="account_id">
                            <option value="">Todos</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select> 
                    </div>                                                    
                </div>   
                
                {{-- TYPE --}}
                <div class="">
                    <x-jet-label for="type" value="Tipo"/>
                    <div>
                        <select name="type" class="py-0 rounded border-gray-400 w-full text-gray-600" id="type">
                            <option value="pdf">PDF</option>                            
                            <option value="excel">Excel</option>  
                        </select> 
                    </div>                                                    
                </div>  
                                
                <button type="submit" class="px-4 py-2 mt-2 bg-orange-500 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">Exportar</button>
            </div>    
        
        </form>        
    </div>

    {{-- Table --}}
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">                                                                                      
                <table class="min-w-full border">
                    <thead>
                        <tr>                                
                            <th class="thead">
                                Número                                
                            </th>

                            <th class="thead">
                                Fecha                               
                            </th>

                            <th class="thead">
                                Tipo
                            </th>

                            <th class="thead">
                                Socio                               
                            </th>

                            <th class="thead">
                                Rubros                               
                            </th>

                            <th class="thead">
                                Referencia                                   
                            </th>

                            <th class="thead">
                                Total                                   
                            </th>                                                        
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @if (count($transactions))  
                            @foreach ($transactions as $item)
                                <tr>
                                    <td class="row">
                                        {{ $item->number }}
                                    </td>
                                    
                                    <td class="row whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}
                                    </td>

                                    <td class="row">
                                        @if ($item->type == 1)
                                            Individual
                                        @elseif ($item->type == 2)
                                            Lotes
                                        @else
                                            Pago
                                        @endif
                                    </td>

                                    <td class="row whitespace-nowrap">                                       
                                        {{ $item->partner->name }} {{ $item->partner->lastname }}                                          
                                    </td>  
                                    
                                    <td class="row whitespace-nowrap">
                                        <ul>
                                            @foreach ($item->content as $i)
                                                <li class=" flex justify-between">{{ $i['name'] }} = <span>{{ number_format($i['price'], 2) }}</span></li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td class="row">
                                        {{ $item->reference }}
                                    </td>

                                    <td class="row whitespace-nowrap">
                                        $ {{ $item->total }}
                                    </td>                                    
                                </tr>
                            @endforeach  
                        @else
                            <tr>
                                <td colspan="7">
                                    <div class="p-4 text-center">
                                        <strong>No se encontraron registros</strong>
                                    </div>
                                </td>
                            </tr>                            
                        @endif                                                  
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class=" text-right pr-4">
                                Total = $ {{ $total }}
                            </td>
                        </tr>
                    </tfoot>
                </table>                
            </div>
        </div>                            
    </div>

    {{-- Pagination --}}
    @if ($transactions->hasPages())
        <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
            {{ $transactions->links() }}   
        </div>
    @endif

    <script>
        $(document).ready(function(){           

            $('#partner_select').select2({
                placeholder: {        
                id: -1,        
                text: "Selecciona un socio"
                }                            
            });         

            $('#partner_select').on('change', function(e){
                let valor = $('#partner_select').select2('val');                

                //   Asignamos el valor o el texto a una variable pública del componente livewire
                @this.set('partner_id', valor);            
            });
        });    

        //  Captura un mensaje enviado por el componente y lo muestra en pantalla
        window.addEventListener('success', event => {                 
            // Reset select2
            $("#accounts_select").val('').trigger('change');      
        });
    </script>
</div>
