<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQrCodeRequest;
use App\Http\Requests\UpdateQrCodeRequest;
use App\Http\Resources\Admin\QrCodeResource;
use App\Models\QrCode;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QrCodeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('qr_code_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new QrCodeResource(QrCode::all());
    }

    public function store(StoreQrCodeRequest $request)
    {
        $qrCode = QrCode::create($request->all());

        return (new QrCodeResource($qrCode))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(QrCode $qrCode)
    {
        abort_if(Gate::denies('qr_code_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new QrCodeResource($qrCode);
    }

    public function update(UpdateQrCodeRequest $request, QrCode $qrCode)
    {
        $qrCode->update($request->all());

        return (new QrCodeResource($qrCode))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(QrCode $qrCode)
    {
        abort_if(Gate::denies('qr_code_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $qrCode->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
