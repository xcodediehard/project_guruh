<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoRequest extends FormRequest
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
            'barang' => ['required', 'exists:barangs,id'],
            'promo' => ['required', 'unique:promos,promo'],
            'code' => ['required', 'unique:promos,code'],
            'diskon' => ['required', 'integer'],
            'date_start' => ['required', 'date'],
            'date_stop' => ['required', 'date'],
            'keterangan' => ['required', 'string']
        ];
    }
}
