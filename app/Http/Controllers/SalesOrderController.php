<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Customer;
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
        if (count($SalesOrders) != 0)
        {
            foreach ($SalesOrders as $SalesOrder) 
            {
                foreach ($SalesOrder->orders as $order) 
                {
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
        // Generate nomor order (ID)
        $nomor_urut = $this->generateOrderNumber();

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
            ->where('address', '=', $request['customer_id'])->get()
            ->first()->id;

        // Get term of payment date
        $term = Customer::select(['term'])->where('id', '=', $request['customer_id'])->get()[0]['term'];
        $request['due_time'] = Carbon::now()
            ->addDays($term)
            ->format('Y-m-d');

        // Table insert for Sales order
        $request['nomor_po'] = strtoupper($request['nomor_po']);
        $request->validate([
            'customer_id' => 'exists:customers,id',
        ]);

        SalesOrder::create($request->all());

        // Table insert for order
        $SalesID = SalesOrder::latest()->get()->first()->id;
        foreach ($request['order'] as $key => $order)
        {
            $order['product_id'] = Product::select(['id'])
                ->where('name', '=', $order['product_id'])->get()
                ->first()->id;

            $order['sales_order_id'] = $SalesID;

            // Array validation
            Validator::make(
                $order,
                [
                    'sales_order_id' => 'exists:sales_orders,id',
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
     * @param  \App\Models\SalesOrder  $SalesOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SalesOrder $order)
    {
        return SalesOrder::with('orders')->find($order->id);
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

        // Update term of payemnt
        $order->due_time = Carbon::parse($request['print_date'])
            ->addDays($order->customer->term)
            ->format('Y-m-d');
        $order->update([
            'due_time' => $order->due_time
        ]);

        return true;
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

    /**
     * Creating new document number.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateOrderNumber()
    {
        // Get recent date
        $year_month = Carbon::now()->format('Y') . '_' . Carbon::now()->format('m');
        
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

        return $nomor_urut;
    }
}
