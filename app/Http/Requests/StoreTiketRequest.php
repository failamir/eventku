<?php

namespace App\Http\Requests;

use App\Models\Tiket;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTiketRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tiket_create');
    }

    public function rules()
    {
        return [
            'no_tiket' => [
                'string',
                'nullable',
            ],
            'qr' => [
                'string',
                'nullable',
            ],
            'no_hp' => [
                'string',
                'nullable',
            ],
            'nama' => [
                'string',
                'nullable',
            ],
            'nik' => [
                'string',
                'nullable',
            ],
            'email' => [
                'string',
                'nullable',
            ],
        ];
    }
}
