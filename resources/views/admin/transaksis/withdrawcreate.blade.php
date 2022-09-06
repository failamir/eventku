@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.transaksi.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.transaksis.withdrawstore") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="invoice">{{ 'ID Withdraw' }}</label>
                <input class="form-control {{ $errors->has('invoice') ? 'is-invalid' : '' }}" type="text" name="invoice" id="invoice" value="{{ 'WD-'.date('is') }}" placeholder="{{ 'WD-'.date('is') }}">
                @if($errors->has('invoice'))
                    <div class="invalid-feedback">
                        {{ $errors->first('invoice') }}
                    </div>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.transaksi.fields.invoice_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="peserta_id">{{ 'Date' }}</label>
                <input class="form-control {{ $errors->has('invoice') ? 'is-invalid' : '' }}" type="datetime" name="created_at" id="created_at" value="{{ date('Y-m-d H:i:s') }}" placeholder="{{ date('Y-m-d H:i:s') }}">
                @if($errors->has('peserta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('peserta') }}
                    </div>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.transaksi.fields.peserta_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="amount">{{ 'Value' }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="text" name="a    mount" id="amount" value="{{ old('amount', '') }}">
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.transaksi.fields.amount_helper') }}</span> --}}
            </div>
            {{-- <div class="form-group">
                <label for="note">{{ trans('cruds.transaksi.fields.note') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{!! old('note') !!}</textarea>
                @if($errors->has('note'))
                    <div class="invalid-feedback">
                        {{ $errors->first('note') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaksi.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="snap_token">{{ trans('cruds.transaksi.fields.snap_token') }}</label>
                <input class="form-control {{ $errors->has('snap_token') ? 'is-invalid' : '' }}" type="text" name="snap_token" id="snap_token" value="{{ old('snap_token', '') }}">
                @if($errors->has('snap_token'))
                    <div class="invalid-feedback">
                        {{ $errors->first('snap_token') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaksi.fields.snap_token_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label>{{ trans('cruds.transaksi.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Transaksi::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaksi.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.transaksi.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="hidden" name="type" id="type" value="{{ 'withdraw' }}">
                {{-- <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Transaksi::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaksi.fields.type_helper') }}</span> --}}
            </div>
            {{-- <div class="form-group">
                <label for="tikets">{{ trans('cruds.transaksi.fields.tiket') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('tikets') ? 'is-invalid' : '' }}" name="tikets[]" id="tikets" multiple>
                    @foreach($tikets as $id => $tiket)
                        <option value="{{ $id }}" {{ in_array($id, old('tikets', [])) ? 'selected' : '' }}>{{ $tiket }}</option>
                    @endforeach
                </select>
                @if($errors->has('tikets'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tikets') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaksi.fields.tiket_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="event_id">{{ trans('cruds.transaksi.fields.event') }}</label>
                <select class="form-control select2 {{ $errors->has('event') ? 'is-invalid' : '' }}" name="event_id" id="event_id">
                    @foreach($events as $id => $entry)
                        <option value="{{ $id }}" {{ old('event_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('event'))
                    <div class="invalid-feedback">
                        {{ $errors->first('event') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaksi.fields.event_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.transaksis.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $transaksi->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection