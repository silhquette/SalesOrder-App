<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'npwp' => 'size:20',
            'name' => 'string|min:4',
            'email' => 'email',
            'term' => 'numeric',
            'address' => 'string|min:10|unique:customers,address',
            'code'=> 'unique:customers,code'
        ];
    }
}
