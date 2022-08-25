@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.withdraw.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.withdraws.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.withdraw.fields.id') }}
                        </th>
                        <td>
                            {{ $withdraw->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.withdraw.fields.kode_withdraw') }}
                        </th>
                        <td>
                            {{ $withdraw->kode_withdraw }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.withdraw.fields.tanggal_withdraw') }}
                        </th>
                        <td>
                            {{ $withdraw->tanggal_withdraw }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.withdraw.fields.jumlah_withdraw') }}
                        </th>
                        <td>
                            {{ $withdraw->jumlah_withdraw }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.withdraw.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Withdraw::STATUS_SELECT[$withdraw->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.withdraws.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection