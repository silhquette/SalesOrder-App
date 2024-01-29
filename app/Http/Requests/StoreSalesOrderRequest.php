<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nomor_po' => 'unique:sales_orders,nomor_po',
            'ppn' => '',
            'order_code' => 'unique:sales_orders,order_code',
            'due_time' => 'date',
            'tanggal_po' => 'date',
        ];
    }
}
