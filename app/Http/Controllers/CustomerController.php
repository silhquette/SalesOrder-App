<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Customer', [
            'customers' => Customer::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uuid = Str::limit(Str::uuid(),8,'');
        return view('CreateCustomer',[
            'status' => 'create',
            'uuid' => 'CS-' . $uuid,
            'create' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create(
            array_map(
                "strtoupper", 
                $request->all()
        ));

        if ($customer) {
            return redirect()->route('customer.create')->with('customerSuccess', 'Data pelanggan berhasil ditambahkan kedalam daftar');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return $customer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('CreateCustomer', [
            'status' => 'edit',
            'customer' => $customer
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $request['name'] = strtoupper($request['name']);
        $request['npwp'] = strtoupper($request['npwp']);
        $request['address'] = strtoupper($request['address']);
        $request['contact'] = strtoupper($request['contact']);
        $request['npwp_add'] = strtoupper($request['npwp_add']);
        $request['code'] = strtoupper($request['code']);
        
        $validated = $request->validate([
            'npwp' => 'size:20',
            'name' => 'string|min:4',
            'email' => 'email',
            'term' => 'numeric',
            'address' => 'string|min:10|unique:customers,address,'.$customer->id,
            'phone' => '',
            'contact' => '',
            'npwp_add' => '',
            'code'=> 'unique:customers,code,'.$customer->id,
        ]);

        $customer->update($validated);

        return redirect()->route('customer.index')->with('editSuccess', 'Data pelanggan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customer.index')->with('deleteSuccess', 'Data produk berhasil dihapus');
    }

    /**
     * Search data in database.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $customer = Customer::all();
        if ($request->keyword != '') {
            $customer = Customer::where('name', 'LIKE', '%' . $request->keyword . '%')
                ->orwhere('code', 'LIKE', '%' . $request->keyword . '%')
                ->orwhere('npwp', 'LIKE', '%' . $request->keyword . '%')
                ->orwhere('contact', 'LIKE', '%' . $request->keyword . '%')
                ->orwhere('address', 'LIKE', '%' . $request->keyword . '%')
                ->get();
        }
        return response()->json([
            'customer' => $customer
        ]);
    }
}
