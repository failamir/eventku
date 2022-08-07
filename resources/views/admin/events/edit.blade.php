@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.event.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.events.update", [$event->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="nama_event">{{ trans('cruds.event.fields.nama_event') }}</label>
                <input class="form-control {{ $errors->has('nama_event') ? 'is-invalid' : '' }}" type="text" name="nama_event" id="nama_event" value="{{ old('nama_event', $event->nama_event) }}">
                @if($errors->has('nama_event'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nama_event') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.nama_event_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="event_code">{{ trans('cruds.event.fields.event_code') }}</label>
                <input class="form-control {{ $errors->has('event_code') ? 'is-invalid' : '' }}" type="text" name="event_code" id="event_code" value="{{ old('event_code', $event->event_code) }}">
                @if($errors->has('event_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('event_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.event_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="harga">{{ trans('cruds.event.fields.harga') }}</label>
                <input class="form-control {{ $errors->has('harga') ? 'is-invalid' : '' }}" type="text" name="harga" id="harga" value="{{ old('harga', $event->harga) }}">
                @if($errors->has('harga'))
                    <div class="invalid-feedback">
                        {{ $errors->first('harga') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.harga_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tanggal_mulai">{{ trans('cruds.event.fields.tanggal_mulai') }}</label>
                <input class="form-control datetime {{ $errors->has('tanggal_mulai') ? 'is-invalid' : '' }}" type="text" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $event->tanggal_mulai) }}">
                @if($errors->has('tanggal_mulai'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tanggal_mulai') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.tanggal_mulai_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tanggal_selesai">{{ trans('cruds.event.fields.tanggal_selesai') }}</label>
                <input class="form-control datetime {{ $errors->has('tanggal_selesai') ? 'is-invalid' : '' }}" type="text" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $event->tanggal_selesai) }}">
                @if($errors->has('tanggal_selesai'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tanggal_selesai') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.tanggal_selesai_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deksripsi">{{ trans('cruds.event.fields.deksripsi') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('deksripsi') ? 'is-invalid' : '' }}" name="deksripsi" id="deksripsi">{!! old('deksripsi', $event->deksripsi) !!}</textarea>
                @if($errors->has('deksripsi'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deksripsi') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.deksripsi_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="image">{{ trans('cruds.event.fields.image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.image_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.events.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $event->id ?? 0 }}');
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

<script>
    Dropzone.options.imageDropzone = {
    url: '{{ route('admin.events.storeMedia') }}',
    maxFilesize: 9, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 9,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="image"]').remove()
      $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($event) && $event->image)
      var file = {!! json_encode($event->image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}

</script>
@endsection