<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            @if ($message = Session::get('deleteSuccess'))
                <div class="bg-green-200 overflow-hidden shadow-sm sm:rounded-lg mb-8 py-4 px-6 text-green-800 flex justify-between" id="flash">
                    <span>
                        <i class="fa-solid fa-circle-check inline-block"></i>
                        <p class="font-bold inline-block pl-2">Awesome!</p>
                        <p class="inline-block pl-2">{{ $message }}</p>
                    </span>
                    <a href="#"><i class="fa-solid fa-xmark text-2xl" id="flash-close"></i></a>
                </div>   
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center">
                        <span class="text-lg">{{ __("Customer List") }}</span>
                        
                        {{-- SEARCH BAR --}}
                        <div class="text-right mr-8 lg:inline-block hidden" id="search">
                            <form action="">
                                <input type="text" class="border-x-0 border-t-0 outline-none focus:ring-0 px-3 pb-1 pt-0 pr-9" id="keyword" placeholder="cari customer atau kode">
                                <i class="fa-solid fa-magnifying-glass ml-[-28px]"></i>
                            </form>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="container overflow-auto rounded-xl relative lg:block hidden shadow-sm rounded-t-lg">
                        <table class="table-auto w-full border border-collapse mt-4 px-3">
                            <thead>
                                <tr class="bg-gradient-to-r from-slate-200 to-slate-200/80">
                                    <th class="p-2">No.</th>
                                    <th class="p-2">Customer Code</th>
                                    <th class="p-2">Customer Name</th>
                                    <th class="p-2">NPWP</th>
                                    <th class="p-2">Contact</th>
                                    <th class="p-2">Address</th>
                                    <th class="p-2 w-36">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($customers->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center py-3 text-gray-600">No data.</td>
                                    </tr>
                                @endif
                                @foreach ($customers as $customer)
                                <tr class="border-t border-b text-center">
                                    <td class="p-2">{{ $loop->iteration }}</td>
                                    <td class="p-2">{{ $customer->code }}</td>
                                    <td class="p-2">{{ $customer->name }}</td>
                                    <td class="p-2">{{ $customer->npwp }}</td>
                                    <td class="p-2">{{ $customer->contact }}</td>
                                    <td class="p-2">{{ Str::limit($customer->address, 18) }}</td>
                                    <td class="p-2">
                                        <a class="edit-button" href="{{ route('customer.edit', $customer->code) }}"><i class="fa-regular fa-pen-to-square text-gray-400 hover:text-gray-600 text-lg px-3"></i></a>
                                        <button value="{{ $customer->code }}" class="delete-button"><i class="fa-solid fa-trash hover:text-[#144272] text-[#2C74B3] text-lg px-3"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- CONTACT LIST --}}
                    <div class="flex flex-col gap-4 mt-4 lg:hidden">
                        @foreach ($customers as $customer)
                        <div class="flex items-center gap-4">
                            <div class="aspect-square h-[50px] rounded-full overflow-hidden">
                                <img src="{{ asset('assets/images/empty.webp') }}" class="object-fit h-full" alt="">
                            </div>
                            <div class="flex-col">
                                <h3 class="font-semibold">{{ $customer->name }}</h3>
                                <h5 class="text-gray-600">{{ $customer->code }} | {{ $customer->contact }}</h5>
                                <address class="text-sm text-gray-400">Ad{{ Str::limit($customer->address, 25) }}dress</address>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 lg:w-fit w-full">
                <div class="p-6 text-gray-900 py-4 lg:w-fit w-full flex items-center justify-end hover:text-[#144272]">
                    <a href="{{ route('customer.create') }}">Register Customer<i class="fa-solid fa-arrow-right ml-3"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- CONFIRMATION --}}
    <div id="delete-wrap" class="hidden">
        <div class="fixed top-0 left-0 right-0 bottom-0 bg-black/70"></div>
        <div class="fixed top-1/2 left-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg -translate-x-1/2 -translate-y-1/2">
            <div class="p-6">
                <span class="text-gray-900 font-bold">Warning!</span>
                <span class="text-gray-700">Apakah anda yakin akan menghapus <span id="customer-name-delete" class=" font-bold"></span> dari database?</span>
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
