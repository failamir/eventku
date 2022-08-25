<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyWithdrawRequest;
use App\Http\Requests\StoreWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;
use App\Models\Withdraw;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WithdrawController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('withdraw_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $withdraws = Withdraw::all();

        return view('admin.withdraws.index', compact('withdraws'));
    }

    public function create()
    {
        abort_if(Gate::denies('withdraw_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.withdraws.create');
    }

    public function store(StoreWithdrawRequest $request)
    {
        $withdraw = Withdraw::create($request->all());

        return redirect()->route('admin.withdraws.index');
    }

    public function edit(Withdraw $withdraw)
    {
        abort_if(Gate::denies('withdraw_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.withdraws.edit', compact('withdraw'));
    }

    public function update(UpdateWithdrawRequest $request, Withdraw $withdraw)
    {
        $withdraw->update($request->all());

        return redirect()->route('admin.withdraws.index');
    }

    public function show(Withdraw $withdraw)
    {
        abort_if(Gate::denies('withdraw_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.withdraws.show', compact('withdraw'));
    }

    public function destroy(Withdraw $withdraw)
    {
        abort_if(Gate::denies('withdraw_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $withdraw->delete();

        return back();
    }

    public function massDestroy(MassDestroyWithdrawRequest $request)
    {
        Withdraw::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
