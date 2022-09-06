<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyWithdrawRequest;
use App\Http\Requests\StoreWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;
use App\Models\Withdraw;
use App\Models\Event;
use App\Models\Tiket;
use App\Models\Transaksi;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use stdClass;

class WithdrawController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('withdraw_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $withdraws = Withdraw::all();
        $transaksis = Transaksi::with(['peserta', 'tikets', 'event', 'created_by'])->where('type','withdraw')->get();

        $users = User::get();

        $tikets = Tiket::get();

        $events = Event::get();

        $total_pemasukan = Transaksi::where('status','success')->sum('amount');
        // $total_pemasukan = $total_pemasukan + Tiket::where('status_payment','LIKE', '%' . 'success' . '%')->sum('total_bayar');
        // var_dump($total_pemasukan);die;

        $etiket_terjual = count(Transaksi::where('status','success')->get());

        $bank = new stdClass();
        $bank->name = 'Bank Loud Island';
        $bank->account_name = 'PT. Loud Island';
        $bank->account_number = '123456789';


        return view('admin.withdraws.index', compact('withdraws','events', 'tikets', 'transaksis', 'users','total_pemasukan','etiket_terjual','bank'));
    }

    public function create()
    {
        abort_if(Gate::denies('withdraw_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.withdraws.create');
    }

    public function store(StoreWithdrawRequest $request)
    {
        $request->input('kode_withdraw', 'WD-'. date('YmdHis'));
        $request->input('status', 'progress');
        // $request->input('tanggal_withdraw');
        // $request->input('jumlah_withdraw');
        $withdraw = Withdraw::create(array_merge($request->all(),['kode_withdraw' => 'WD-'. date('YmdHis'),'status' => 'progress']));

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
