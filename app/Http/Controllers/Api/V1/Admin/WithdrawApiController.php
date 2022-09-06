<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;
use App\Http\Resources\Admin\WithdrawResource;
use App\Models\Withdraw;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WithdrawApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('withdraw_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WithdrawResource(Withdraw::all());
    }

    public function store(StoreWithdrawRequest $request)
    {
        $withdraw = Withdraw::create($request->all());

        return (new WithdrawResource($withdraw))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Withdraw $withdraw)
    {
        abort_if(Gate::denies('withdraw_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WithdrawResource($withdraw);
    }

    public function update(UpdateWithdrawRequest $request, Withdraw $withdraw)
    {
        $withdraw->update($request->all());

        return (new WithdrawResource($withdraw))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Withdraw $withdraw)
    {
        abort_if(Gate::denies('withdraw_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $withdraw->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
