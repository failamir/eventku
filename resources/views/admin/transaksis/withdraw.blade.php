@extends('layouts.admin')
@section('content')
@can('transaksi_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.transaksis.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.transaksi.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Transaksi', 'route' => 'admin.transaksis.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.transaksi.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Transaksi">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.transaksi.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaksi.fields.invoice') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaksi.fields.peserta') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaksi.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaksi.fields.snap_token') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaksi.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaksi.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaksi.fields.tiket') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaksi.fields.event') }}
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.event_code') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($users as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\Transaksi::STATUS_SELECT as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\Transaksi::TYPE_SELECT as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($tikets as $key => $item)
                                    <option value="{{ $item->no_tiket }}">{{ $item->no_tiket }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($events as $key => $item)
                                    <option value="{{ $item->nama_event }}">{{ $item->nama_event }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksis as $key => $transaksi)
                        <tr data-entry-id="{{ $transaksi->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $transaksi->id ?? '' }}
                            </td>
                            <td>
                                {{ $transaksi->invoice ?? '' }}
                            </td>
                            <td>
                                {{ $transaksi->peserta->name ?? '' }}
                            </td>
                            <td>
                                {{ $transaksi->peserta->email ?? '' }}
                            </td>
                            <td>
                                {{ $transaksi->amount ?? '' }}
                            </td>
                            <td>
                                {{ $transaksi->snap_token ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Transaksi::STATUS_SELECT[$transaksi->status] ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Transaksi::TYPE_SELECT[$transaksi->type] ?? '' }}
                            </td>
                            <td>
                                @foreach($transaksi->tikets as $key => $item)
                                    <span class="badge badge-info">{{ $item->no_tiket }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $transaksi->event->nama_event ?? '' }}
                            </td>
                            <td>
                                {{ $transaksi->event->event_code ?? '' }}
                            </td>
                            <td>
                                @can('transaksi_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.transaksis.show', $transaksi->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('transaksi_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.transaksis.edit', $transaksi->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('transaksi_delete')
                                    <form action="{{ route('admin.transaksis.destroy', $transaksi->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('transaksi_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.transaksis.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Transaksi:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection