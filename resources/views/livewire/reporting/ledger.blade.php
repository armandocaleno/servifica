<div>    

    <div class=" bg-gray-50 border-2 px-4 pt-2 pb-6 mb-4">
        <form action="{{ route('reporting.ledger.pdf') }}" target="_blank">
            @csrf
            {{-- filters --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 xl:grid-cols-7 gap-4">           
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

                {{-- accounts --}}
                <div class=" col-span-3">
                    <x-jet-label value="Cuenta contable"/>
                    <div wire:ignore>
                        <select name="accounting_id" class="py-0 rounded border-gray-400 w-full text-gray-600" id="accounting_select">
                            <option value="-1">Seleccione</option>
                            @foreach ($accountings as $account)
                                <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                            @endforeach
                        </select> 
                    </div>                                                    
                </div>  

                {{-- TYPE --}}
                <div class="">
                    <x-jet-label for="type" value="Tipo"/>
                    <div>
                        <select name="type" class="py-0 rounded border-gray-400 w-full text-gray-600" id="type">
                            <option value="pdf" selected>PDF</option>                            
                            <option value="excel">Excel</option>  
                        </select> 
                    </div>                                                    
                </div>  
                          
                @can('accounting.ledger.export') 
                    <button type="submit" class="px-4 py-2 mt-2 bg-orange-500 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 
                                                focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition"
                                                >Exportar
                    </button>
                @endcan
            </div>    
        </form>
    </div>

    {{-- Table --}}
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">                                                                                      
                <table class="min-w-full border">
                    @php
                        $total_debe = 0;
                        $total_haber = 0;
                        $saldo = 0;
                    @endphp
                    <thead>
                        <tr>                                
                            <th class="thead">
                               Fecha                              
                            </th>

                            <th class="thead">
                                Concepto                                
                            </th>

                            <th class="thead">
                                Debe                               
                            </th>

                            <th class="thead">
                                Haber                               
                            </th>

                            <th class="thead">
                                Saldo                                   
                            </th>

                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @if (count($journals))  
                            @foreach ($journals as $item)
                                @php
                                    $total_debe += $item->debit_value;
                                    $total_haber += $item->credit_value;
                                @endphp
                                <tr>
                                    <td class="row">
                                        {{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}
                                    </td>

                                    <td class="row">
                                        {{ $item->reference }}
                                    </td>

                                    <td class="row text-right whitespace-nowrap">   
                                        @if ($item->debit_value > 0)
                                            $ {{ number_format($item->debit_value, 2) }}   
                                        @else
                                            -
                                        @endif                                                                         
                                    </td>  

                                    <td class="row text-right whitespace-nowrap">                                       
                                        @if ($item->credit_value > 0)
                                            $ {{ number_format($item->credit_value, 2) }}   
                                        @else
                                            -
                                        @endif                                     
                                    </td> 

                                    <td class="row text-right whitespace-nowrap">
                                        {{-- $ {{ number_format($item->balance, 2) }} --}}
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
                    <tfoot>
                        <tr>
                            <td></td>
                            <td class=" text-right">Totales</td>
                            <td class="px-6 text-right">$ {{ number_format($total_debe, 2) }}</td>
                            <td class="px-6 text-right">$ {{ number_format($total_haber, 2) }}</td>
                            <td class="px-6 text-right">
                               @if ($account_type)
                                    @if ($account_type->id == 1)
                                        $ {{ number_format($total_debe - $total_haber, 2) }}
                                    @else
                                        $ {{ number_format($total_haber - $total_debe, 2) }}
                                    @endif
                                @else
                                    $ {{ number_format(0, 2) }}
                               @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>                
            </div>
        </div>                            
    </div>

    <script>
        $(document).ready(function(){           

            $('#accounting_select').select2({
                placeholder: {        
                id: -1,        
                text: "Selecciona una cuenta"
                }                            
            });         

            $('#accounting_select').on('change', function(e){
                let valor = $('#accounting_select').select2('val');                

                //   Asignamos el valor o el texto a una variable pública del componente livewire
                @this.set('accounting_id', valor);            
            });
        });    
    </script>
</div>

