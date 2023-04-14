<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Document;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DocumentController extends Controller
{
    /**
     * Print the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function printInvoice(PurchaseOrder $order)
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
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $order) {
        return view('Document', [
            'purchaseOrder' => $order,
            'subtotal' => 0
        ]);
    }

    /**
     * Print the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function printSuratJalan(PurchaseOrder $order)
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
