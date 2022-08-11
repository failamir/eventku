<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreTiketRequest;
use App\Http\Requests\UpdateTiketRequest;
use App\Http\Resources\Admin\TiketResource;
use App\Models\Tiket;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TiketApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('tiket_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TiketResource(Tiket::with(['peserta', 'event'])->get());
    }

    public function store(StoreTiketRequest $request)
    {
        $tiket = Tiket::create($request->all());

        return (new TiketResource($tiket))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Tiket $tiket)
    {
        abort_if(Gate::denies('tiket_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TiketResource($tiket->load(['peserta', 'event']));
    }

    public function update(UpdateTiketRequest $request, Tiket $tiket)
    {
        $tiket->update($request->all());

        return (new TiketResource($tiket))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Tiket $tiket)
    {
        abort_if(Gate::denies('tiket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tiket->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
