@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.tiket.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tikets.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="no_tiket">{{ trans('cruds.tiket.fields.no_tiket') }}</label>
                <input class="form-control {{ $errors->has('no_tiket') ? 'is-invalid' : '' }}" type="text" name="no_tiket" id="no_tiket" value="{{ old('no_tiket', '') }}">
                @if($errors->has('no_tiket'))
                    <div class="invalid-feedback">
                        {{ $errors->first('no_tiket') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.no_tiket_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="peserta_id">{{ trans('cruds.tiket.fields.peserta') }}</label>
                <select class="form-control select2 {{ $errors->has('peserta') ? 'is-invalid' : '' }}" name="peserta_id" id="peserta_id">
                    @foreach($pesertas as $id => $entry)
                        <option value="{{ $id }}" {{ old('peserta_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('peserta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('peserta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.peserta_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.tiket.fields.checkin') }}</label>
                <select class="form-control {{ $errors->has('checkin') ? 'is-invalid' : '' }}" name="checkin" id="checkin">
                    <option value disabled {{ old('checkin', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Tiket::CHECKIN_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('checkin', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('checkin'))
                    <div class="invalid-feedback">
                        {{ $errors->first('checkin') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.checkin_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notes">{{ trans('cruds.tiket.fields.notes') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{!! old('notes') !!}</textarea>
                @if($errors->has('notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.notes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="qr">{{ trans('cruds.tiket.fields.qr') }}</label>
                <input class="form-control {{ $errors->has('qr') ? 'is-invalid' : '' }}" type="text" name="qr" id="qr" value="{{ old('qr', '') }}">
                @if($errors->has('qr'))
                    <div class="invalid-feedback">
                        {{ $errors->first('qr') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.qr_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.tiket.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Tiket::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', 'valid') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.tiket.fields.status_payment') }}</label>
                <select class="form-control {{ $errors->has('status_payment') ? 'is-invalid' : '' }}" name="status_payment" id="status_payment">
                    <option value disabled {{ old('status_payment', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Tiket::STATUS_PAYMENT_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status_payment', 'pending') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status_payment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status_payment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.status_payment_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.tiket.fields.type_payment') }}</label>
                <select class="form-control {{ $errors->has('type_payment') ? 'is-invalid' : '' }}" name="type_payment" id="type_payment">
                    <option value disabled {{ old('type_payment', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Tiket::TYPE_PAYMENT_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type_payment', 'cash') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type_payment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type_payment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.type_payment_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="no_hp">{{ trans('cruds.tiket.fields.no_hp') }}</label>
                <input class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}" type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', '') }}">
                @if($errors->has('no_hp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('no_hp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.no_hp_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.tiket.fields.nama') }}</label>
                <input class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" type="text" name="nama" id="nama" value="{{ old('nama', '') }}">
                @if($errors->has('nama'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nama') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.nama_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nik">{{ trans('cruds.tiket.fields.nik') }}</label>
                <input class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}" type="text" name="nik" id="nik" value="{{ old('nik', '') }}">
                @if($errors->has('nik'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nik') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.nik_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.tiket.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', '') }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tiket.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="event_id">{{ trans('cruds.tiket.fields.event') }}</label>
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
                <span class="help-block">{{ trans('cruds.tiket.fields.event_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.tikets.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $tiket->id ?? 0 }}');
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