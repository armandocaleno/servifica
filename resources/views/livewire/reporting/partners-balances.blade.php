<div>    
    <form action="{{ route('reporting.partners.balances.generate') }}" method="post" target="_blank">
        @csrf
        <div class="flex mb-4 space-x-2">        
            {{-- Search --}}                   
            <x-jet-input type="text" name="search" class="flex-1" wire:model="search" placeholder="Buscar por apellido, identificación o código..."/>  

            {{-- TYPE --}}
            <div class="">
                <x-jet-label for="type" value="Tipo"/>
                <div>
                    <select name="type" class="py-0 rounded border-gray-400 w-full text-gray-600" id="partner_select">
                        <option value="pdf">PDF</option>                            
                        <option value="excel">Excel</option>  
                    </select> 
                </div>                                                    
            </div>  

            <button type="submit" class="px-4 py-2 bg-orange-500 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">Exportar</button>            
        </div> 
    </form>
    
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">       
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>                            
                            <th class="thead">Código</th>     
                            <th class="thead">Socio</th>   
                            @foreach ($accounts as $account)
                                <th class="thead">{{ $account->name }}</th>  
                            @endforeach                                                                                                                                    
                        </tr>                        
                    </thead>
                    <tbody class="bg-white">                            
                        @if (count($balances))
                            @foreach ($balances as $item)                           
                                <tr>                                                                     
                                    <td class="row whitespace-nowrap">{{ $item->code }}</td>
                                    <td class="row whitespace-nowrap">{{ $item->name }} {{ $item->lastname }}</td>   
                                    @foreach ($accounts as $account)
                                        @php
                                            $var = "cuenta".$account->id;
                                        @endphp
                                        @if ($item->$var == null || $item->$var == 0)
                                            <td class="row whitespace-nowrap text-center">-</td> 
                                        @else
                                            <td class="row whitespace-nowrap text-right">$ {{ $item->$var }}</td> 
                                        @endif
                                        
                                    @endforeach                                                                                                       
                                </tr>                                   
                            @endforeach                            
                        @else
                            <tr>
                                <td colspan="{{ count($accounts)+1 }}" class="text-center text-gray-400">No existen datos</td>
                            </tr>                            
                        @endif                                                                    
                    </tbody>                    
                </table>
            </div>
        </div>
    </div>
</div>
