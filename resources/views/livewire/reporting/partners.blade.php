<div>    

    <div class=" bg-gray-50 border-2 px-4 pt-2 pb-6 mb-4">
        <form action="{{ route('reporting.partners.generate') }}" method="post" target="_blank">
        @csrf
        
            {{-- filters --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4">                             
                
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
                                Código                         
                            </th>

                            <th class="thead">
                                Identificación                              
                            </th>

                            <th class="thead">
                                Nombres
                            </th>

                            <th class="thead">
                                Apellidos                               
                            </th>

                            <th class="thead">
                                Teléfono                               
                            </th>        
                            
                            <th class="thead">
                                Email                               
                            </th> 
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @if ($partners->count())  
                            @foreach ($partners as $item)
                                <tr>
                                    <td class="row">
                                        {{ $item->code }}
                                    </td>
                                    
                                    <td class="row whitespace-nowrap">
                                        {{ $item->identity }}
                                    </td>

                                    <td class="row whitespace-nowrap">
                                        {{ $item->name }}  
                                    </td>

                                    <td class="row whitespace-nowrap">                                       
                                        {{ $item->lastname }}
                                    </td>  
                                    
                                    <td class="row">
                                        {{ $item->phone }}                                        
                                    </td> 
                                    
                                    <td class="row">
                                        {{ $item->email }}                                        
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
                </table>                
            </div>
        </div>                            
    </div>

    {{-- Pagination --}}
    @if ($partners->hasPages())
        <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
            {{ $partners->links() }}   
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
