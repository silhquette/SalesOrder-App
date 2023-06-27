<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\StoreDocumentRequest;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Documents = Document::latest()->get();
        return view('Document.document-list', [
            'documents' => $Documents,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\SalesOrder  $order
     * @return \Illuminate\Http\Response
     */
    public function create(SalesOrder $order)
    {
        return view('Document.document-create', [
            'sales_order' => $order,
            'subtotal' => 0
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentRequest $request)
    {
        // Generate document number
        $current_month = Carbon::now()->format('m');
        $current_year = Carbon::now()->year;
        $prev_doc = Document::select(['document_number'])
            ->where('created_at', 'like', $current_year . '-' . $current_month . '%')
            ->latest()->get();
        count($prev_doc) ? $doc_number = (int)$prev_doc->first()->document_number + 1 : $doc_number = 1;
        
        // Table insert for Document
        $data = [
            'document_number' => $doc_number,
            'document_code' =>$current_year . $current_month . $doc_number,
            'print_date' => $request->print_date
        ];
        Document::create($data);

        Document::latest()->get()->first()->orders()->syncWithoutDetaching($request->order_id);

        return redirect()->route('document.index')->with('Success', 'Data berhasil masuk ke Database!');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        $subtotal = 0;
        foreach ($document->orders as $order) {
            $subtotal += $order->amount;
        }
        $order_id = [];
        foreach ($document->orders as $order) {
            array_push($order_id, $order->id);
        }

        return view('Document.document-edit', [
            'order_id' => $order_id,
            'document' => $document,
            'subtotal' => $subtotal,
            'sales_order' => $document->orders->first()->salesOrder
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\StoreDocumentRequest  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDocumentRequest $request, Document $document)
    {
        $document->update(['print_date' => $request->print_date]);
        $document->orders()->sync($request->order_id);
        foreach ($document->orders as $index => $order) {
            if (isset(array_values($request->keterangan)[$index])) {
                $order->pivot->update(['additional' => array_values($request->keterangan)[$index]]);
            }
        }
        
        return redirect()->route('document.show', $document->document_code)->with('Success', 'Data berhasil diubah!');
    }

    /**
     * Show the specified resource.
     *
     * @param  \App\Models\Document  $SalesOrder
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        $subtotal = 0;
        foreach ($document->orders as $order) {
            $subtotal += $order->amount;
        }
        $order_id = [];
        foreach ($document->orders as $order) {
            array_push($order_id, $order->id);
        }
        return view('Document.document-details', [
            'order_id' => $order_id,
            'document' => $document,
            'subtotal' => $subtotal,
            'sales_order' => $document->orders->first()->salesOrder
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $Document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        Document::where('document_code', $document->document_code)->delete();
        return redirect()->route('document.index')->with('deleteSuccess', 'Data Dokumen berhasil dihapus');
    }

    /**
     * Print the specified resource.
     *
     * @param  \App\Models\Document  $order
     * @return \Illuminate\Http\Response
     */
    public function printInvoice(Document $document)
    {
        // Get latest document number
        $document_number = $document->document_number;

        // Set document number
        if ($document_number < 10) {
            $document_number = '00' . $document_number;
        } elseif ($document_number < 100) {
            $document_number = '0' . $document_number;
        }

        // Wrapping data
        $datas = [
            'orders' => $document->orders,
            'print_date' => $document->print_date,
            'purchase_order' => $document->orders->first()->salesOrder,
            'total_product' => 0,
            'document_number' => $document_number,
            'subtotal' => 0
        ];
        $pdf = PDF::loadview('Invoice_PDF', $datas)->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    /**
     * Print the specified resource.
     *
     * @param  \App\Models\Document  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function printSuratJalan(Document $document)
    {
        // Get latest document number
        $document_number = $document->document_number;

        // Set document number
        if ($document_number < 10) {
            $document_number = '00' . $document_number;
        } elseif ($document_number < 100) {
            $document_number = '0' . $document_number;
        }

        // Wrapping data
        $datas = [
            'orders' => $document->orders,
            'print_date' => $document->print_date,
            'purchase_order' => $document->orders->first()->salesOrder,
            'total_product' => 0,
            'document_number' => $document_number
        ];
        $pdf = PDF::loadview('SuratJalan_PDF', $datas)->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    /**
     * Download the specified resource.
     *
     * @param  \App\Models\Document  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function downloadSuratJalan(Document $document)
    {
        // Get latest document number
        $document_number = $document->document_number;

        // Set document number
        if ($document_number < 10) {
            $document_number = '00' . $document_number;
        } elseif ($document_number < 100) {
            $document_number = '0' . $document_number;
        }

        // Wrapping data
        $datas = [
            'orders' => $document->orders,
            'print_date' => $document->print_date,
            'purchase_order' => $document->orders->first()->salesOrder,
            'total_product' => 0,
            'document_number' => $document_number
        ];
        $pdf = PDF::loadview('SuratJalan_PDF', $datas)->setPaper('a4', 'potrait');
        return $pdf->download();
    }

    /**
     * Download the specified resource.
     *
     * @param  \App\Models\Document  $order
     * @return \Illuminate\Http\Response
     */
    public function downloadInvoice(Document $document)
    {
        // Get latest document number
        $document_number = $document->document_number;

        // Set document number
        if ($document_number < 10) {
            $document_number = '00' . $document_number;
        } elseif ($document_number < 100) {
            $document_number = '0' . $document_number;
        }

        // Wrapping data
        $datas = [
            'orders' => $document->orders,
            'print_date' => $document->print_date,
            'purchase_order' => $document->orders->first()->salesOrder,
            'total_product' => 0,
            'document_number' => $document_number,
            'subtotal' => 0
        ];
        $pdf = PDF::loadview('Invoice_PDF', $datas)->setPaper('a4', 'potrait');
        return $pdf->download();
    }
}
