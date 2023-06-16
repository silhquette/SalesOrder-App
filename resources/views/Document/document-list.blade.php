<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('Success'))
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
                        <span class="text-lg">{{ __("Document List") }}</span>
                    </div>

                    {{-- table --}}
                    <div class="container overflow-auto rounded-xl relative shadow-sm rounded-t-lg">
                        <table class="table-auto w-full border border-collapse mt-4 px-3">
                            <thead>
                                <tr class="bg-gradient-to-r from-slate-200 to-slate-200/80">
                                    <th class="p-2">No.</th>
                                    <th class="p-2">Document Code</th>
                                    <th class="p-2">Order ID</th>
                                    <th class="p-2">Customer</th>
                                    <th class="p-2">Item</th>
                                    <th class="p-2">Address</th>
                                    <th class="p-2">Printed at</th>
                                    <th class="p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($documents->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center py-3 text-gray-600">No data.</td>
                                    </tr>
                                @endif
                                @foreach ($documents as $document)
                                <tr class="border-t border-b text-center">
                                    <td class="p-2">{{ $loop->iteration }}</td>
                                    <td class="p-2">{{ $document->first()->document_code }}</td>
                                    <td class="p-2">{{ $document->first()->orders->first()->salesOrder->order_code }}</td>
                                    <td class="p-2">{{ $document->first()->orders->first()->salesOrder->customer->name }}</td>
                                    <td class="p-2">{{ count($document->first()->orders) }}</td>
                                    <td class="p-2">{{ Str::limit($document->first()->orders->first()->salesOrder->customer->address, 18) }}</td>
                                    <td class="p-2">{{ $document->first()->print_date }}</td>
                                    <td class="p-2">
                                        <a class="show-button" href="{{ route('document.show', $document->first()->document_code) }}"><i class="fa-regular fa-eye text-gray-400 hover:text-gray-600 text-lg px-3"></i></a>
                                        <button value="{{ $document->first()->document_code }}" class="delete-button"><i class="fa-solid fa-trash hover:text-[#144272] text-[#2C74B3] text-lg px-3"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 w-fit">
                <div class="p-6 text-gray-900 py-4 w-fit flex items-center hover:text-[#144272]">
                    <a href="{{ route('document.create') }}">Create Document<i class="fa-solid fa-arrow-right ml-3"></i></a>
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
                <span class="text-gray-700">Apakah anda yakin akan menghapus <span id="document-code-delete" class=" font-bold"></span> dari database?</span>
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
