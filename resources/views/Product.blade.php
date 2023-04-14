<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('DeleteSuccess'))
                <div class="bg-green-200 overflow-hidden shadow-sm sm:rounded-lg mb-8 py-4 px-6 text-green-800 flex justify-between" id="flash">
                    <span>
                        <i class="fa-solid fa-circle-check inline-block"></i>
                        <p class="font-bold inline-block pl-2">Awesome!</p>
                        <p class="inline-block pl-2">{{ $message }}</p>
                    </span>
                    <a href="#"><i class="fa-solid fa-xmark text-2xl" id="flash-close"></i></a>
                </div>   
            @endif
            @if ($message = Session::get('DeleteFailed'))
                <div class="bg-red-200 overflow-hidden shadow-sm sm:rounded-lg mb-8 py-4 px-6 text-red-800 flex justify-between" id="flash">
                    <span>
                        <i class="fa-solid fa-circle-check inline-block"></i>
                        <p class="font-bold inline-block pl-2">Ouch, sorry!</p>
                        <p class="inline-block pl-2">{{ $message }}</p>
                    </span>
                    <a href="#"><i class="fa-solid fa-xmark text-2xl" id="flash-close"></i></a>
                </div>   
            @endif
            @if ($message = Session::get('editSuccess'))
                <div class="bg-green-200 overflow-hidden shadow-sm sm:rounded-lg mb-8 py-4 px-6 text-green-800 flex justify-between" id="flash">
                    <span>
                        <i class="fa-solid fa-circle-check inline-block"></i>
                        <p class="font-bold inline-block pl-2">Great!</p>
                        <p class="inline-block pl-2">{{ $message }}</p>
                    </span>
                    <a href="#"><i class="fa-solid fa-xmark text-2xl" id="flash-close"></i></a>
                </div>   
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between">
                        <span class="text-lg">{{ __("Product Registration Form") }}</span>
                        <a href="#"><i class="fa-sharp fa-solid fa-angle-up mr-2" id="formToggle"></i></a>
                    </div>

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('product.store') }}" id="input-form">
                        <div class="flex justify-center mt-4">
                            <div class="gap-4 w-4/5 grid grid-cols-5">
                                @csrf
                        
                                <!-- Product Code -->
                                <div class="w-full ">
                                    <x-input-label for="code" :value="__('Product Code')" />
                                    <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required autofocus />
                                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                                </div>
                        
                                <!-- Product Name -->
                                <div class="w-full col-span-2">
                                    <x-input-label for="name" :value="__('Product name')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                
                                <!-- Product Dimension -->
                                <div class="w-full ">
                                    <x-input-label for="dimension" :value="__('Product Dimension (mm)')" />
                                    <x-text-input id="dimension" class="block mt-1 w-full" type="text" name="dimension" :value="old('dimension')" required autofocus />
                                    <x-input-error :messages="$errors->get('dimension')" class="mt-2" />
                                </div>
                            
                                <!-- Product unit -->
                                <div class="w-full ">
                                    <x-input-label for="unit" :value="__('Product Unit')" />
                                    <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit" :value="old('unit')" required autofocus />
                                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                                </div>
                                
                            </div>

                            {{-- dropzone --}}
                            
                        </div>
                        <!-- Submit Button -->
                        <div class="flex items-center flex-col gap-4 justify-end mt-6">
                            <x-primary-button class="w-1/2 min-w-[200px]">
                                {{ __('Submit') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center">
                        <span class="text-lg">{{ __("Product List") }}</span>
                        
                        {{-- SEARCH --}}
                        <div class="text-right mr-8 inline-block" id="search">
                            <form action="">
                                <input type="text" class="border-x-0 border-t-0 outline-none focus:ring-0 px-3 pb-1 pt-0 pr-9" id="keyword" placeholder="cari barang atau kode">
                                <i class="fa-solid fa-magnifying-glass ml-[-28px]"></i>
                            </form>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="container overflow-auto rounded-xl relative shadow-sm rounded-t-lg">
                        <table class="table-fixed w-full border border-collapse mt-4 px-3">
                            <thead>
                                <tr class="bg-gradient-to-r from-slate-200 to-slate-200/80">
                                    <th class="p-2">No.</th>
                                    <th class="p-2">Product Code</th>
                                    <th class="p-2 w-80">Product Name</th>
                                    <th class="p-2">Dimension</th>
                                    <th class="p-2">Unit</th>
                                    <th class="p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($products->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center py-3 text-gray-600">No data.</td>
                                    </tr>
                                @endif
                                @foreach ($products as $product)
                                <tr class="border-t border-b text-center">
                                    <td class="p-2">{{ $loop->iteration }}</td>
                                    <td class="p-2">{{ $product->code }}</td>
                                    <td class="p-2 w-80">{{ $product->name }}</td>
                                    <td class="p-2">{{ $product->dimension }}</td>
                                    <td class="p-2">{{ $product->unit }}</td>
                                    <td class="p-2">
                                        <button value="{{ $product->id }}" class="edit-button"><i class="fa-regular fa-pen-to-square text-gray-400 hover:text-gray-600 text-lg px-3"></i></button>
                                        <button value="{{ $product->id }}" class="delete-button"><i class="fa-solid fa-trash hover:text-[#144272] text-[#2C74B3] text-lg px-3"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
           
        </div>
    </div>

    {{-- edit form --}}
    <div id="edit-wrap" class="hidden">
        <div class="fixed top-0 left-0 right-0 bottom-0 bg-black/70"></div>
        <div class="fixed top-1/2 left-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg -translate-x-1/2 -translate-y-1/2 p-6 text-gray-900 w-2/3">
            <div class="flex justify-between items-center">
                <span class="text-lg text-gray-900">{{ __("Product Edit") }}</span>
                <a href="#"><i class="fa-solid fa-xmark text-2xl mr-2" id="edit-close"></i></a>
            </div>
            <form method="POST" id="edit-form">
                <div class="flex justify-center mt-4">
                    <div class="gap-4 w-4/5 grid grid-cols-5">
                        @csrf
                        @method('PATCH')
                        <!-- Product Code -->
                        <div class="w-full">
                            <x-input-label for="code" :value="__('Product Code')" />
                            <x-text-input id="code" class="block mt-1 w-full" type="text" name="edit-code" :value="old('edit-code')" required autofocus />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>
                
                        <!-- Product Name -->
                        <div class="w-full col-span-2">
                            <x-input-label for="name" :value="__('Product name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="edit-name" :value="old('edit-name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        <!-- Product Dimension -->
                        <div class="w-full">
                            <x-input-label for="dimension" :value="__('Product Dimension (mm)')" />
                            <x-text-input id="dimension" class="block mt-1 w-full" type="text" name="edit-dimension" :value="old('edit-dimension')" required autofocus />
                            <x-input-error :messages="$errors->get('dimension')" class="mt-2" />
                        </div>
                            
                        <!-- Product unit -->
                        <div class="w-full ">
                            <x-input-label for="unit" :value="__('Product Unit')" />
                            <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit" :value="old('unit')" required autofocus />
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>
                    
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="flex items-center flex-col gap-4 justify-end mt-6">
                    <x-primary-button class="w-1/2 min-w-[200px]">
                        {{ __('Submit') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    {{-- confirmation --}}
    <div id="delete-wrap" class="hidden">
        <div class="fixed top-0 left-0 right-0 bottom-0 bg-black/70"></div>
        <div class="fixed top-1/2 left-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg -translate-x-1/2 -translate-y-1/2">
            <div class="p-6">
                <span class="text-gray-900 font-bold">Warning!</span>
                <span class="text-gray-700">Apakah anda yakin akan menghapus barang <span id="product-name-delete" class=" font-bold"></span> dari database?</span>
                <form method="POST" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <div class="mt-4 flex gap-4 justify-end">
                        <button id="delete-close" class="cancel-button inline-flex justiy-center items-cefnter px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-[#144272] uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#144272] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Cancel</button>
                        <button type="submit" class="delete-confirm inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-[#144272] to-[#2C74B3] border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-[#144272] focus:bg-[#144272] active:bg-[#144272] focus:outline-none focus:ring-2 focus:ring-[#144272] focus:ring-offset-2 transition ease-in-out duration-150">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</x-app-layout>
