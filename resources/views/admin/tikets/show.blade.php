@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.tiket.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tikets.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.id') }}
                        </th>
                        <td>
                            {{ $tiket->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.no_tiket') }}
                        </th>
                        <td>
                            {{ $tiket->no_tiket }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.peserta') }}
                        </th>
                        <td>
                            {{ $tiket->peserta->email ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.checkin') }}
                        </th>
                        <td>
                            {{ App\Models\Tiket::CHECKIN_SELECT[$tiket->checkin] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.notes') }}
                        </th>
                        <td>
                            {!! $tiket->notes !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.qr') }}
                        </th>
                        <td>
                            {{ $tiket->qr }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Tiket::STATUS_SELECT[$tiket->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.status_payment') }}
                        </th>
                        <td>
                            {{ App\Models\Tiket::STATUS_PAYMENT_SELECT[$tiket->status_payment] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.type_payment') }}
                        </th>
                        <td>
                            {{ App\Models\Tiket::TYPE_PAYMENT_SELECT[$tiket->type_payment] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.no_hp') }}
                        </th>
                        <td>
                            {{ $tiket->no_hp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.nama') }}
                        </th>
                        <td>
                            {{ $tiket->nama }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.nik') }}
                        </th>
                        <td>
                            {{ $tiket->nik }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.email') }}
                        </th>
                        <td>
                            {{ $tiket->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tiket.fields.event') }}
                        </th>
                        <td>
                            {{ $tiket->event->nama_event ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tikets.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#tiket_transaksis" role="tab" data-toggle="tab">
                {{ trans('cruds.transaksi.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="tiket_transaksis">
            @includeIf('admin.tikets.relationships.tiketTransaksis', ['transaksis' => $tiket->tiketTransaksis])
        </div>
    </div>
</div>

@endsection