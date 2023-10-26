<div>    

    <div class=" bg-gray-50 border-2 px-4 pt-2 pb-6 mb-4">
        <form action="{{ route('reporting.expenses.generate') }}" method="post" target="_blank">
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

                {{-- suppliers --}}
                <div class="">
                    <x-jet-label for="supplier" value="Proveedor"/>
                    <div wire:ignore>
                        <select name="supplier_id" class="py-0 rounded border-gray-400 w-full text-gray-600" id="supplier_select">
                            <option value="">Todos</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select> 
                    </div>                                                    
                </div>   
                                
                @can('reporting.expenses.export') 
                    <button type="submit" class="px-4 py-2 mt-2 bg-orange-500 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80
                                             focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition"
                                             >Exportar</button>
                @endcan
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
                                No. Factura                                
                            </th>

                            <th class="thead">
                                No. de Autorización                                
                            </th>

                            <th class="thead">
                                Fecha                               
                            </th>

                            <th class="thead">
                                Proveedor                               
                            </th>

                            <th class="thead">
                                RUC/CI                                   
                            </th>

                            <th class="thead">
                                Subtotal                                   
                            </th>  

                            <th class="thead">
                                IVA                                   
                            </th> 
                            
                            <th class="thead">
                                Total                                   
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @if (count($expenses))  
                            @foreach ($expenses as $item)
                                <tr>
                                    <td class="row">
                                        {{ $item->number }}
                                    </td>

                                    <td class="row">
                                        {{ $item->auth_number }}
                                    </td>
                                    
                                    <td class="row whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}
                                    </td>

                                    <td class="row whitespace-nowrap">                                       
                                        {{ $item->suppliers->name }}                                         
                                    </td>  

                                    <td class="row whitespace-nowrap">                                       
                                        {{ $item->suppliers->identity }}                                         
                                    </td> 

                                    <td class="row whitespace-nowrap">
                                        $ {{ number_format($item->total - $item->tax, 2) }}
                                    </td> 

                                    <td class="row whitespace-nowrap">
                                        $ {{ $item->tax }}
                                    </td>
                                    
                                    <td class="row whitespace-nowrap">
                                        $ {{ $item->total }}
                                    </td>
                                </tr>
                            @endforeach  
                        @else
                            <tr>
                                <td colspan="6">
                                    <div class="p-4 text-center">
                                        <strong>No se encontraron registros</strong>
                                    </div>
                                </td>
                            </tr>                            
                        @endif                                                  
                    </tbody>
                    {{-- <tfoot>
                        <tr>
                            <td colspan="6" class=" text-right pr-4">
                                Total = $ {{ $total }}
                            </td>
                        </tr>
                    </tfoot> --}}
                </table>                
            </div>
        </div>                            
    </div>

    {{-- Pagination --}}
    @if ($expenses->hasPages())
        <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
            {{ $expenses->links() }}   
        </div>
    @endif

    <script>
        $(document).ready(function(){           

            $('#supplier_select').select2({
                placeholder: {        
                id: -1,        
                text: "Selecciona un socio"
                }                            
            });         

            $('#supplier_select').on('change', function(e){
                let valor = $('#supplier_select').select2('val');                

                //   Asignamos el valor o el texto a una variable pública del componente livewire
                @this.set('supplier_id', valor);            
            });
        });    
    </script>
</div>
