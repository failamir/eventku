@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.event.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.events.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.id') }}
                        </th>
                        <td>
                            {{ $event->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.nama_event') }}
                        </th>
                        <td>
                            {{ $event->nama_event }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.event_code') }}
                        </th>
                        <td>
                            {{ $event->event_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.harga') }}
                        </th>
                        <td>
                            {{ $event->harga }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.tanggal_mulai') }}
                        </th>
                        <td>
                            {{ $event->tanggal_mulai }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.tanggal_selesai') }}
                        </th>
                        <td>
                            {{ $event->tanggal_selesai }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.image') }}
                        </th>
                        <td>
                            @if($event->image)
                                <a href="{{ $event->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $event->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.deskripsi') }}
                        </th>
                        <td>
                            {!! $event->deskripsi !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.events.index') }}">
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
            <a class="nav-link" href="#event_transaksis" role="tab" data-toggle="tab">
                {{ trans('cruds.transaksi.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#event_tikets" role="tab" data-toggle="tab">
                {{ trans('cruds.tiket.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="event_transaksis">
            @includeIf('admin.events.relationships.eventTransaksis', ['transaksis' => $event->eventTransaksis])
        </div>
        <div class="tab-pane" role="tabpanel" id="event_tikets">
            @includeIf('admin.events.relationships.eventTikets', ['tikets' => $event->eventTikets])
        </div>
    </div>
</div>

@endsection