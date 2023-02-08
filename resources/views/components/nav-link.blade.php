@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center text-sm font-semibold leading-5 text-[#144272] bg-gray-200 h-fit py-1 px-4 rounded-lg outline-transparent outline-offset-0 focus:outline-[#2C74B3] focus:outline-offset-2 transition duration-150 ease-in-out'
            : 'inline-flex items-center text-sm font-medium leading-5 text-gray-500 h-fit py-1 px-4 rounded-lg outline-transparent outline-offset-0 focus:outline-[#144272] focus:outline-offset-2 transition duration-150 ease-in-out';
@endphp

{{-- @php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp --}}

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
