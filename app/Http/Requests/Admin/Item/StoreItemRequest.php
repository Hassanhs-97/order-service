<?php

namespace App\Http\Requests\Admin\Item;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'customer_name'     => 'required|max:255',
            'customer_address'  => 'required|max:255',
            'order_description' => 'required|max:255',
            'price'             => 'required|integer',
        ];
    }
}
