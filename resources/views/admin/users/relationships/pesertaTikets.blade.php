@can('tiket_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.tikets.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.tiket.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.tiket.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-pesertaTikets">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.no_tiket') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.peserta') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.checkin') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.qr') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.status_payment') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.type_payment') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.no_hp') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.nama') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.nik') }}
                        </th>
                        <th>
                            {{ trans('cruds.tiket.fields.email') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tikets as $key => $tiket)
                        <tr data-entry-id="{{ $tiket->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $tiket->id ?? '' }}
                            </td>
                            <td>
                                {{ $tiket->no_tiket ?? '' }}
                            </td>
                            <td>
                                {{ $tiket->peserta->email ?? '' }}
                            </td>
                            <td>
                                {{ $tiket->peserta->name ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Tiket::CHECKIN_SELECT[$tiket->checkin] ?? '' }}
                            </td>
                            <td>
                                {{ $tiket->qr ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Tiket::STATUS_SELECT[$tiket->status] ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Tiket::STATUS_PAYMENT_SELECT[$tiket->status_payment] ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Tiket::TYPE_PAYMENT_SELECT[$tiket->type_payment] ?? '' }}
                            </td>
                            <td>
                                {{ $tiket->no_hp ?? '' }}
                            </td>
                            <td>
                                {{ $tiket->nama ?? '' }}
                            </td>
                            <td>
                                {{ $tiket->nik ?? '' }}
                            </td>
                            <td>
                                {{ $tiket->email ?? '' }}
                            </td>
                            <td>
                                @can('tiket_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.tikets.show', $tiket->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('tiket_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.tikets.edit', $tiket->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('tiket_delete')
                                    <form action="{{ route('admin.tikets.destroy', $tiket->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('tiket_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tikets.massDestroy') }}",
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
  let table = $('.datatable-pesertaTikets:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection