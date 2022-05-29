<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'merek' => ['required', 'exists:mereks,id'],
            'barang' => ['required', 'unique:barangs,barang'],
            'katerangan' => ['required', 'string'],
            'harga' => ['required', 'numeric'],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg,jfif'],
            'stok.*' => ['required', 'numeric'],
            'size.*' => ['required', 'numeric'],
        ];
    }
}
