<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('PO', [
            'PO' => PurchaseOrder::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $year_month = Carbon::now()->format('Y') . '_' . Carbon::now()->format('m');
        $nomor_urut = PurchaseOrder::select('order_code')->where('created_at', 'like', $year_month . '%')->limit(1)->get();
        if (count($nomor_urut) == 0) {
            $nomor_urut = '001';
        } else {
            $nomor_urut = (int)substr($nomor_urut[0]->order_code, -2);
            $nomor_urut += 1;
            if ($nomor_urut <10) {
                $nomor_urut = '00' . $nomor_urut;
            } elseif ($nomor_urut <100) {
                $nomor_urut = '0' . $nomor_urut;
            }
        }

        return view('CreatePO', [
            'uuid' => Carbon::now()->format('y') . Carbon::now()->format('m') . $nomor_urut,
            'customers' => Customer::select(['name','code','id','address'])->get(),
            'products' => Product::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseOrderRequest $request)
    {
        $request['customer_id'] = explode(' - ', $request->customer_id)[1];
        $request['customer_id'] = Customer::select(['id'])
            ->where('address', '=', $request['customer_id'])
            ->get()[0]
            ->id;
        
        $term = Customer::select(['term'])->where('id', '=', $request['customer_id'])->get()[0]['term'];
        $request['due_time'] = Carbon::now()
            ->addDays($term)
            ->format('Y-m-d');

        // table insert for purchase order
        $validatedPO = $request->validate([
            'customer_id' => 'exists:customers,id',
            'nomor_po' => 'unique:purchase_orders,nomor_po',
            'total' => '',
            'ppn' => '',
            'order_code' => 'unique:purchase_orders,order_code',
            'due_time' => 'date',
            'tanggal_po' => 'date',
        ]);

        PurchaseOrder::create($validatedPO);

        $PurchaseID = PurchaseOrder::latest()->get()[0]['id'];
        foreach ($request['order'] as $key => $order) {
            $order['product_id'] = Product::select(['id'])
                ->where('name', '=', $order['product_id'])
                ->get()[0]
                ->id;

            $order['purchase_order_id'] = $PurchaseID;

            // array validation
            Validator::make(
                $order,
                [
                    'purchase_order_id' => 'exists:purchase_orders,id',
                    'product_id' => 'exists:products,id'
                ]
            );

            Order::create($order);
        }
            
        return redirect()->route('order.create')->with('success', 'Data sales order berhasil ditambahkan kedalam daftar');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $order)
    {
        return $order;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseOrderRequest  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $order)
    {
        for ($i=0; $i < count($request->keterangan); $i++) { 
            Order::find($request->id[$i])->update([
                'keterangan' => $request->keterangan[$i]
            ]);
        }

        $validatedDate = $request->validate([
            'print_date' => 'date'
        ]);
        $order->update([
            'print_date' => $validatedDate['print_date']
        ]);

        return redirect()->route('order.Suratjalan', $order->order_code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $order)
    {
        $order->delete();
        return redirect()->route('order.index')->with('deleteSuccess', 'Data Sales Order berhasil dihapus');
    }

    public function printSuratJalan(PurchaseOrder $order)
    {

        $datas = [
            'order' => $order->toArray(),
            'total_product' => 0,
        ];
        $pdf = PDF::loadview('SuratJalan_PDF', $datas)->setPaper('a4', 'potrait');
	    return $pdf->stream();
    }

    public function printInvoice(PurchaseOrder $order)
    {
        $datas = [
            'order' => $order->toArray(),
            'subtotal' => 0,
        ];
        $pdf = PDF::loadview('Invoice_PDF', $datas)->setPaper('a4', 'potrait');
	    return $pdf->stream();
    }

    public function showSuratJalan(PurchaseOrder $order) {
        return view('showSuratJalan', [
            'purchaseOrder' => $order,
            'subtotal' => 0
        ]);
    }

    /**
     * Search data in database.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $purchaseOrder = PurchaseOrder::all();
        if ($request->keyword != '') {
            $purchaseOrder = PurchaseOrder::where('order_number', 'LIKE', '%' . $request->keyword . '%')
                ->orwhere('order_code', 'LIKE', '%' . $request->keyword . '%')
                ->orWhereHas('customer', function($query){
                    global $request;
                    $query->where('name', 'LIKE', '%' . $request->keyword . '%');  
                })
                ->orWhereHas('customer', function($query){
                    global $request;
                    $query->where('address', 'LIKE', '%' . $request->keyword . '%');  
                })
                ->get();
        }
        return response()->json([
            'purchaseOrder' => $purchaseOrder
        ]);
    }
}
