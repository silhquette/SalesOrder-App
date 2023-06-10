<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create(
            array_map(
                "strtoupper", 
                $request->all()
        ));

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
     * @param  Illuminate\Http\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {   $product->update(
            array_map(
                "strtoupper", 
                $request->all()
        ));

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
        if (count($product->orders)) {
            return redirect()->route('product.index')->with('DeleteFailed', 'Data produk masih tercantum pada order');
        } else {
            $product->delete();
            return redirect()->route('product.index')->with('DeleteSuccess', 'Data produk berhasil dihapus');
        }
    }

    /**
     * Search the specified dataset in database.
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
