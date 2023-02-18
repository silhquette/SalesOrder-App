<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Product', [
            'products' => Product::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['code'] = strtoupper($request['code']);
        $request['name'] = strtoupper($request['name']);
        $request['dimension'] = strtoupper($request['dimension']);
        $request['unit'] = strtoupper($request['unit']);

        $validated = $request->validate([
            'code' => 'unique:products,code|string|min:4',
            'name' => 'unique:products,name|string|min:4',
            'dimension' => '',
            'unit' => ''
        ]);

        $product = Product::create($validated);

        if ($product) {
            return redirect()->route('product.index')->with('productSuccess', 'Data produk berhasil ditambahkan kedalam daftar');
        } else {
            return redirect()->route('product.index')->with('productFailed', 'Data produk gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request['code'] = strtoupper($request['code']);
        $request['name'] = strtoupper($request['name']);
        $request['dimension'] = strtoupper($request['dimension']);
        $request['unit'] = strtoupper($request['unit']);
        
        $validated = $request->validate([
            'edit-code' => 'string|min:4',
            'edit-name' => 'string|min:4',
            'edit-dimension' => ''
        ]);

        $product->name = $validated['edit-name'];
        $product->code = $validated['edit-code'];
        $product->dimension = $validated['edit-dimension'];
        $product->save();

        return redirect()->route('product.index')->with('editSuccess', 'Data produk berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('product.index')->with('productSuccess', 'Data produk berhasil dihapus');
    }

    /**
     * Search data in database.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $product = Product::all();
        if ($request->keyword != '') {
            $product = Product::where('name', 'LIKE', '%' . $request->keyword . '%')
                ->orwhere('code', 'LIKE', '%' . $request->keyword . '%')
                ->get();
        }
        return response()->json([
            'product' => $product
        ]);
    }
}
