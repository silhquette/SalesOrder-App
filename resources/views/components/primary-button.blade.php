<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-[#144272] to-[#2C74B3] border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-[#144272] focus:bg-[#144272] active:bg-[#144272] focus:outline-none focus:ring-2 focus:ring-[#144272] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
