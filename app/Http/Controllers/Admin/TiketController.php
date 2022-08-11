<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTiketRequest;
use App\Http\Requests\StoreTiketRequest;
use App\Http\Requests\UpdateTiketRequest;
use App\Models\Event;
use App\Models\Tiket;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TiketController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('tiket_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Tiket::with(['peserta', 'event'])->select(sprintf('%s.*', (new Tiket())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'tiket_show';
                $editGate = 'tiket_edit';
                $deleteGate = 'tiket_delete';
                $crudRoutePart = 'tikets';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('no_tiket', function ($row) {
                return $row->no_tiket ? $row->no_tiket : '';
            });
            $table->addColumn('peserta_email', function ($row) {
                return $row->peserta ? $row->peserta->email : '';
            });

            $table->editColumn('peserta.name', function ($row) {
                return $row->peserta ? (is_string($row->peserta) ? $row->peserta : $row->peserta->name) : '';
            });
            $table->editColumn('checkin', function ($row) {
                return $row->checkin ? Tiket::CHECKIN_SELECT[$row->checkin] : '';
            });
            $table->editColumn('qr', function ($row) {
                return $row->qr ? $row->qr : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Tiket::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('status_payment', function ($row) {
                return $row->status_payment ? Tiket::STATUS_PAYMENT_SELECT[$row->status_payment] : '';
            });
            $table->editColumn('type_payment', function ($row) {
                return $row->type_payment ? Tiket::TYPE_PAYMENT_SELECT[$row->type_payment] : '';
            });
            $table->editColumn('no_hp', function ($row) {
                return $row->no_hp ? $row->no_hp : '';
            });
            $table->editColumn('nama', function ($row) {
                return $row->nama ? $row->nama : '';
            });
            $table->editColumn('nik', function ($row) {
                return $row->nik ? $row->nik : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->addColumn('event_nama_event', function ($row) {
                return $row->event ? $row->event->nama_event : '';
            });

            $table->editColumn('event.event_code', function ($row) {
                return $row->event ? (is_string($row->event) ? $row->event : $row->event->event_code) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'peserta', 'event']);

            return $table->make(true);
        }

        $users  = User::get();
        $events = Event::get();

        return view('admin.tikets.index', compact('users', 'events'));
    }

    public function create()
    {
        abort_if(Gate::denies('tiket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pesertas = User::pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        $events = Event::pluck('nama_event', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tikets.create', compact('events', 'pesertas'));
    }

    public function store(StoreTiketRequest $request)
    {
        $tiket = Tiket::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $tiket->id]);
        }

        return redirect()->route('admin.tikets.index');
    }

    public function edit(Tiket $tiket)
    {
        abort_if(Gate::denies('tiket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pesertas = User::pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        $events = Event::pluck('nama_event', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tiket->load('peserta', 'event');

        return view('admin.tikets.edit', compact('events', 'pesertas', 'tiket'));
    }

    public function update(UpdateTiketRequest $request, Tiket $tiket)
    {
        $tiket->update($request->all());

        return redirect()->route('admin.tikets.index');
    }

    public function show(Tiket $tiket)
    {
        abort_if(Gate::denies('tiket_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tiket->load('peserta', 'event', 'tiketTransaksis');

        return view('admin.tikets.show', compact('tiket'));
    }

    public function destroy(Tiket $tiket)
    {
        abort_if(Gate::denies('tiket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tiket->delete();

        return back();
    }

    public function massDestroy(MassDestroyTiketRequest $request)
    {
        Tiket::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('tiket_create') && Gate::denies('tiket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Tiket();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
