@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.withdraw.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.withdraws.update", [$withdraw->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="kode_withdraw">{{ trans('cruds.withdraw.fields.kode_withdraw') }}</label>
                <input class="form-control {{ $errors->has('kode_withdraw') ? 'is-invalid' : '' }}" type="text" name="kode_withdraw" id="kode_withdraw" value="{{ old('kode_withdraw', $withdraw->kode_withdraw) }}">
                @if($errors->has('kode_withdraw'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kode_withdraw') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.withdraw.fields.kode_withdraw_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tanggal_withdraw">{{ trans('cruds.withdraw.fields.tanggal_withdraw') }}</label>
                <input class="form-control date {{ $errors->has('tanggal_withdraw') ? 'is-invalid' : '' }}" type="text" name="tanggal_withdraw" id="tanggal_withdraw" value="{{ old('tanggal_withdraw', $withdraw->tanggal_withdraw) }}">
                @if($errors->has('tanggal_withdraw'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tanggal_withdraw') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.withdraw.fields.tanggal_withdraw_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="jumlah_withdraw">{{ trans('cruds.withdraw.fields.jumlah_withdraw') }}</label>
                <input class="form-control {{ $errors->has('jumlah_withdraw') ? 'is-invalid' : '' }}" type="number" name="jumlah_withdraw" id="jumlah_withdraw" value="{{ old('jumlah_withdraw', $withdraw->jumlah_withdraw) }}" step="1">
                @if($errors->has('jumlah_withdraw'))
                    <div class="invalid-feedback">
                        {{ $errors->first('jumlah_withdraw') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.withdraw.fields.jumlah_withdraw_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.withdraw.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Withdraw::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $withdraw->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.withdraw.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection