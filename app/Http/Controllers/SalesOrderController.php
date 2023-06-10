<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Set total amount
        $SalesOrders = SalesOrder::latest()->get();
        if (count($SalesOrders) != 0) {
            foreach ($SalesOrders as $SalesOrder) {
                foreach ($SalesOrder->orders as $order) {
                    $SalesOrder['total'] += $order->amount;
                }
            }
        }
        
        return view('PO', [
            'PO' => $SalesOrders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get recent date
        $year_month = Carbon::now()->format('Y') . '_' . Carbon::now()->format('m');
        
        // Generate nomor order (ID)
        $nomor_urut = SalesOrder::select('order_code')->where('created_at', 'like', $year_month . '%')->latest()->limit(1)->get();
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
     * @param  \App\Http\Requests\StoreSalesOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalesOrderRequest $request)
    {
        // Get customer_id
        $request['customer_id'] = explode(' - ', $request->customer_id)[1];
        $request['customer_id'] = Customer::select(['id'])
            ->where('address', '=', $request['customer_id'])
            ->get()[0]
            ->id;
        // Get term of payment date
        $term = Customer::select(['term'])->where('id', '=', $request['customer_id'])->get()[0]['term'];
        $request['due_time'] = Carbon::now()
            ->addDays($term)
            ->format('Y-m-d');

        // Table insert for Sales order
        $request['nomor_po'] = strtoupper($request['nomor_po']);
        $validatedPO = $request->validate([
            'customer_id' => 'exists:customers,id',
            'nomor_po' => 'unique:Sales_orders,nomor_po',
            'ppn' => '',
            'order_code' => 'unique:Sales_orders,order_code',
            'due_time' => 'date',
            'tanggal_po' => 'date',
        ]);

        SalesOrder::create($validatedPO);

        // Table insert for order
        $SalesID = SalesOrder::latest()->get()[0]['id'];
        foreach ($request['order'] as $key => $order) {
            $order['product_id'] = Product::select(['id'])
                ->where('name', '=', $order['product_id'])
                ->get()[0]
                ->id;

            $order['Sales_order_id'] = $SalesID;

            // Array validation
            Validator::make(
                $order,
                [
                    'Sales_order_id' => 'exists:Sales_orders,id',
                    'product_id' => 'exists:products,id'
                ]
            );

            Order::create($order);
        }

        // Generate document number
        $prev_doc = Document::select(['month','document_number'])->latest()->get();
        if (count($prev_doc)) {
            $doc_number = (int)$prev_doc[0]['document_number'] + 1;
        } else {
            $doc_number = 1;
        }
        
        // Generate month and year
        $doc_month = Carbon::parse($request['print_date'])->month;
        $doc_year = Carbon::parse($request['print_date'])->year;
        
        // Table insert for Document
        $newest_order = SalesOrder::latest()->limit(1)->get()[0]['orders'];
        foreach ($newest_order as $selected_order) {
            $data = [
                'order_id' => $selected_order["id"],
                'document_number' => $doc_number,
                'month' => $doc_month,
                'year' => $doc_year,
                'print_date' => Carbon::now()
            ];

            Document::create($data);
        }
        
        return redirect()->route('order.create')->with('success', 'Data sales order berhasil ditambahkan kedalam daftar');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrder  $SalesOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SalesOrder $order)
    {
        return $order->with('orders')->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrder  $SalesOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesOrder $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSalesOrderRequest  $request
     * @param  \App\Models\SalesOrder  $SalesOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalesOrderRequest $request, SalesOrder $order)
    {
        // Update field keterangan
        for ($i=0; $i < count($request->keterangan); $i++) { 
            Order::find($request->id[$i])->update([
                'keterangan' => $request->keterangan[$i]
            ]);
        }

        // Validasi date input
        $validatedDate = $request->validate([
            'print_date' => 'date'
        ]);

        // Update term of payemnt
        $order->due_time = Carbon::parse($request['print_date'])
            ->addDays($order->customer->term)
            ->format('Y-m-d');
        $order->update([
            'due_time' => $order->due_time
        ]);
            
        // Generate document number
        $prev_doc = Document::select(['month','document_number'])->latest()->get();
        if (count($prev_doc)) {
            $doc_number = (int)$prev_doc[0]['document_number'] + 1;
        } else {
            $doc_number = 1;
        }

        // Generate month and year
        $doc_month = Carbon::parse($request['print_date'])->month;
        $doc_year = Carbon::parse($request['print_date'])->year;
        // Table insert for Document
        foreach ($request->order as $selected_order) {
            $data = [
                'order_id' => $selected_order["order_id"],
                'document_number' => $doc_number,
                'month' => $doc_month,
                'year' => $doc_year,
                'print_date' => $validatedDate['print_date']
            ];

            Document::create($data);
        }

        return redirect()->route('document.generate', $order->order_code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrder  $SalesOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesOrder $order)
    {
        $order->delete();
        return redirect()->route('order.index')->with('deleteSuccess', 'Data Sales Order berhasil dihapus');
    }

    /**
     * Search specified dataset in database.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $SalesOrder = SalesOrder::all();
        if ($request->keyword != '') {
            $SalesOrder = SalesOrder::where('order_number', 'LIKE', '%' . $request->keyword . '%')
                ->orwhere('order_number', 'LIKE', '%' . $request->keyword . '%')
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
            'SalesOrder' => $SalesOrder
        ]);
    }
}
