<?php

namespace App\Http\Requests;

use App\Models\Withdraw;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWithdrawRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('withdraw_edit');
    }

    public function rules()
    {
        return [
            'kode_withdraw' => [
                'string',
                'nullable',
            ],
            'tanggal_withdraw' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'jumlah_withdraw' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
