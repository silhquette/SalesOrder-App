<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Document;
use Illuminate\Http\Request;
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
        $Documents = Document::latest()->get()->groupBy('document_code');
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
     * Print the specified resource.
     *
     * @param  \App\Models\SalesOrder  $order
     * @return \Illuminate\Http\Response
     */
    public function printInvoice(SalesOrder $order)
    {
        // Get latest document number
        $latest_document_number = Document::select(['document_number'])->latest()->limit(1)->get()[0]['document_number'];

        // Get selected order
        $selected_order = Document::where('document_number', '=', $latest_document_number)->get();

        // Set document number
        if ($latest_document_number <10) {
            $latest_document_number = '00' . $latest_document_number;
        } elseif ($latest_document_number <100) {
            $latest_document_number = '0' . $latest_document_number;
        }

        // Wrapping data
        $datas = [
            'selected_order' => $selected_order->toArray(),
            'print_date' => $selected_order[0]['print_date'],
            'purchase_order' => $order,
            'total_product' => 0,
            'document_number' => $latest_document_number,
            'subtotal' => 0
        ];
        $pdf = PDF::loadview('Invoice_PDF', $datas)->setPaper('a4', 'potrait');
	    return $pdf->stream();
    }
    
    /**
     * Show the specified resource.
     *
     * @param  \App\Models\Document  $SalesOrder
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document) {
        $documents = Document::where('document_number', $document->document_number)->get();
        $subtotal = 0;
        foreach ($documents as $document) {
            $subtotal += $document->order->amount;
        }
        return view('Document.document-details', [
            'documents' => $documents,
            'subtotal' => $subtotal,
            'sales_order' => $documents->first()->order->salesOrder
        ]);
    }

    /**
     * Print the specified resource.
     *
     * @param  \App\Models\SalesOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function printSuratJalan(SalesOrder $order)
    {
        // Get latest document number
        $latest_document_number = Document::select(['document_number'])->latest()->limit(1)->get()[0]['document_number'];

        // Get selected order
        $selected_order = Document::where('document_number', '=', $latest_document_number)->get();

        // Set document number
        if ($latest_document_number <10) {
            $latest_document_number = '00' . $latest_document_number;
        } elseif ($latest_document_number <100) {
            $latest_document_number = '0' . $latest_document_number;
        }

        // Wrapping data
        $datas = [
            'selected_order' => $selected_order->toArray(),
            'print_date' => $selected_order[0]['print_date'],
            'purchase_order' => $order,
            'total_product' => 0,
            'document_number' => $latest_document_number
        ];
        $pdf = PDF::loadview('SuratJalan_PDF', $datas)->setPaper('a4', 'potrait');
	    return $pdf->stream();
    }
}
