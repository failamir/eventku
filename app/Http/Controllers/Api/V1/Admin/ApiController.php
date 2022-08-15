<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Midtrans\Snap;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPendaftarRequest;
use App\Http\Requests\StorePendaftarRequest;
use App\Http\Requests\UpdatePendaftarRequest;
// use App\Http\Resources\Admin\PendaftarResource;
use App\Http\Resources\Admin\UserResource;
use App\Models\Event;
// use App\Models\Pendaftar;
use App\Models\Transaksi;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use stdClass;
use Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tiket;
// use Dotenv\Validator;
date_default_timezone_set("Asia/Jakarta");

class ApiController extends Controller
{
    public function __construct()
    {
        // Set midtrans configuration
        \Midtrans\Config::$serverKey    = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
    }
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('pendaftar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pendaftars = Tiket::with(['event'])->get();

        $events = Event::get();

        return view('admin.pendaftars.index', compact('events', 'pendaftars'));
    }

    public function pendaftar()
    {
        // abort_if ( Gate::denies( 'pendaftar_access' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        return new UserResource(User::paginate(10));
    }



    public function create()
    {
        abort_if(Gate::denies('pendaftar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = Event::pluck('nama_event', 'id')->prepend(trans('global.pleaseSelect'), '');
        $no_t = Tiket::orderBy('no_tiket', 'DESC')->first();
        return view('admin.pendaftars.create', compact('events', 'no_t'));
    }

    public function apibeli(Request $request)
    {
        return view('admin.pendaftars.beli', compact('request'));
    }

    public function beli(Request $request)
    {

        $events = Event::pluck('nama_event', 'id')->prepend(trans('global.pleaseSelect'), '');
        $no_t = Tiket::orderBy('no_tiket', 'DESC')->first();
        $data = $request->all();
        $data['price_1']  = $data['day_1'] * 210000;
        $data['price_2']  = $data['day_2'] * 210000;
        $data['price_3']  = $data['day_3'] * 280000;

        if ($data['day_1'] == 0 && $data['day_2'] == 0 && $data['day_3'] == 0) {
            return view('welcome');
        } else {
            return view('daftar', compact('events', 'no_t', 'data'));
        }
        // return redirect()->route( 'admin.pendaftars.index' );
        // var_dump( $request->all() );
        // echo '<pre> dev ';
    }

    public function generate(Request $request)
    {
        $data = $request->all();
        // dd( $data );
        $length = 10;
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
        }

        $no_invoice = 'TRX-' . Str::upper($random);
        // } else {

        $tiket_id = array();
        $amount = 0;

        $u1 = 12000;

        for ($u = 11600; $u < $u1; $u++) {
            $no_tiket = $u;
            $tiket_id[] = $no_tiket;
            // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
            // $total_bayar = Event::find( 1 )->harga;
            // $amount += $total_bayar;
            $code = uniqid() . uniqid();
            $pendaftar = Tiket::create(array_merge($request->all(), [
                'nama' => 'generate',
                'nik' => 'generate',
                'email' => $code,
                'no_hp' => $no_tiket,
                'no_tiket' => 'generate',
                // 'total_bayar' => $total_bayar,
                // 'token' => $request->input( '_token' ),
                'status_payment' => 'pending',
            ]));
            // QrCode::format( 'png' );
            //Will return a png image
            QrCode::format('png')->size(300)->generate($code, '../public/qrcodes/' . $u . '.png');
        }

        echo ' berhasil';
        //  return view( 'bayar', compact( 'snap' ) );
        // }
        // return redirect()->route( 'admin.pendaftars.index' );
    }

    public function list_checkin()
    {
        // abort_if ( Gate::denies( 'pendaftar_access' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );
        $s = $_GET['uid'];
        // if ($s == 'sudahdanterpakai') {
        $data = Tiket::with(['event'])->where('pic', $s)->orWhere('checkin', 'sudah')->orWhere('checkin', 'sudah-note')->orWhere('checkin', 'terpakai')->OrderBy('updated_at', 'ASC')->limit(30)->get();
        // else {return new UserResource(Tiket::with(['event'])->where('checkin',$s)->OrderBy('updated_at','ASC')->paginate(10));}

        $snap = new stdClass();
        $snap->checkin = count(Tiket::where('pic', $s)->orWhere('checkin', 'sudah')->orWhere('checkin', 'sudah-note')->get());
        $snap->checkout = count(Tiket::where('pic', $s)->orWhere('checkin', 'terpakai')->get());
        $snap->data = $data;

        return response()->json($snap);
    }

    public function list_tiket()
    {
        // abort_if ( Gate::denies( 'pendaftar_access' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );
        if (isset($_GET['uid']) == false) {
            $snap = new stdClass();
            $snap->data = 'UID Kosong';
            return response(json_encode($snap), Response::HTTP_FORBIDDEN);
        }
        return new UserResource(Tiket::with(['event'])->where('no_tiket', '!=', 'generate')->where('pic_assign', $_GET['uid'])->where('qr', '!=', 'NULL')->paginate(10));
    }

    public function checkin(Request $request)
    {
        $pendaftar = Tiket::where('qr', $request->input('qr'))->first();
        if (empty($pendaftar)) {
            $snap = new stdClass();
            // $snap->code = $request->input('qr');
            // $snap->checkin = $pendaftar->checkin;
            $snap->data = 'QR not Found';
            return response(json_encode($snap), Response::HTTP_FORBIDDEN);
        }
        // var_dump( $pendaftar );
        $pendaftar->update(['checkin' => 'sudah', 'pic_checkin' => $request->input('uid')]);
        $snap = new stdClass();
        $snap->data = 'success';
        return response()->json($snap);
    }

    public function qrcheck(Request $request)
    {
        $pendaftar = Tiket::where('qr', $request->input('qr'))->first();
        // var_dump( $pendaftar );
        if (empty($pendaftar)) {
            $snap = new stdClass();
            $snap->data = 'QR not Found';
            return response(json_encode($snap), Response::HTTP_FORBIDDEN);
        }
        if ($pendaftar->checkin == 'sudah-note') {
            // $pendaftar->update(['checkin' => 'sudah']);
            $snap = new stdClass();
            $snap->code = $request->input('qr');
            $snap->checkin = $pendaftar->checkin;
            $snap->note = '*There’s a problem with this QR, hold on and make a data validation';
            return response()->json($snap);
        }
        if ($pendaftar->checkin == 'sudah') {
            // $pendaftar->update(['checkin' => 'sudah']);
            $snap = new stdClass();
            $snap->code = $request->input('qr');
            $snap->checkin = $pendaftar->checkin;
            $snap->note = 'sudah checkin';
            return response()->json($snap);
        } else {
            // $pendaftar->update(['checkin' => 'sudah']);
            $snap = new stdClass();
            if ($pendaftar->checkin == null) $pendaftar->checkin = 'belum';
            $snap->code = $request->input('qr');
            $snap->checkin = $pendaftar->checkin;
            $snap->note = '';
            return response()->json($snap);
        }
    }

    public function scanqr(Request $request)
    {
        $pendaftar = Tiket::where('qr', $request->input('qr'))->orWhere('email', $request->input('qr'))->first();
        if (empty($pendaftar)) {
            $snap = new stdClass();
            $snap->data = 'QR not Found';
            return response(json_encode($snap), Response::HTTP_FORBIDDEN);
        }
        $snap = new stdClass();
        if ($pendaftar->checkin == null) $pendaftar->checkin = 'belum';
        if ($pendaftar->no_tiket == 'generate') $pendaftar->no_tiket = '';
        $snap->code = $request->input('qr');
        $snap->checkin = $pendaftar->checkin;
        $snap->no_tiket = $pendaftar->no_tiket;
        return response()->json($snap);
        // }
    }

    public function checkticket(Request $request)
    {
        $pendaftar = Tiket::with('event')->where('no_tiket', $request->input('no_tiket'))->first();
        if (empty($pendaftar)) {
            $snap = new stdClass();
            $snap->data = 'Tiket not Found';
            return response(json_encode($snap), Response::HTTP_FORBIDDEN);
        }
        $snap = new stdClass();
        if ($pendaftar->checkin == null) $pendaftar->checkin = 'belum';

        $snap->data = $pendaftar;
        return response()->json($snap);
        // }
    }

    public function assignticket(Request $request)
    {
        $pendaftar = Tiket::where('no_tiket', $request->input('no_tiket'))->first();
        if (empty($pendaftar)) {
            $snap = new stdClass();
            $snap->data = 'Tiket not Found';
            return response(json_encode($snap), Response::HTTP_FORBIDDEN);
        }
        $pendaftar->update(['qr' =>  $request->input('qr'),'pic_assign' => $request->input('uid')]);
        $snap = new stdClass();
        if ($pendaftar->checkin == null) $pendaftar->checkin = 'belum';

        $snap->data = $pendaftar;
        return response()->json($snap);
        // }
    }

    public function status_tiket(Request $request)
    {
        $pendaftar = Tiket::where('qr', $request->input('qr'))->first();
        // var_dump( $pendaftar );
        // $pendaftar->update( [ 'checkin' =>'sudah' ] );
        $snap = new stdClass();
        $snap->data = $pendaftar;
        return response()->json($snap);
    }

    public function checkout(Request $request)
    {
        $pendaftar = Tiket::where('qr', $request->input('qr'))->first();
        if (empty($pendaftar)) {
            $snap = new stdClass();
            // $snap->code = $request->input('qr');
            // $snap->checkin = $pendaftar->checkin;
            $snap->data = 'QR not Found';
            return response(json_encode($snap), Response::HTTP_FORBIDDEN);
        }
        $pendaftar->update(['checkin' => 'terpakai', 'pic_checkout' => $request->input('uid')]);
        $snap = new stdClass();
        $snap->data = 'success';
        return response()->json($snap);
    }

    public function checkin2(Request $request)
    {
        $pendaftar = Tiket::where('qr', $request->input('qr'))->first();
        if (empty($pendaftar)) {
            $snap = new stdClass();
            // $snap->code = $request->input('qr');
            // $snap->checkin = $pendaftar->checkin;
            $snap->data = 'QR not Found';
            return response(json_encode($snap), Response::HTTP_FORBIDDEN);
        }
        $pendaftar->update(['checkin' => 'sudah-note']);
        $snap = new stdClass();
        $snap->data = 'success';
        // $snap->note = "*There’s a problem with this QR, hold on and make a data validation";
        return response()->json($snap);
    }

    public function daftar(Request $request)
    {
        $e_user = User::where(
            'email',
            $request->input('email')
            // 'password' => $request->input( 'no_hp' ),
        )->first();

        if (!empty($e_user)) {

            $snap = new stdClass();
            $snap->data = 'email sudah terdaftar';
            return response()->json($snap);
        } else {
            $user = User::create([
                'uid'     => $request->input('uid'),
                'email'    => $request->input('email'),
                'name'    => $request->input('name'),
                'password' => $request->input('uid'),
                // 'password' => $request->input( 'no_hp' ),
            ]);
            // $user->assignRole( 'User' );
            $user->roles()->sync(2);

            $snap = new stdClass();
            $snap->data = 'success daftar';
            return response()->json($snap);
        }
    }

    public function updateprofile(Request $request)
    {
        $e_user = User::where(
            'uid',
            $request->input('uid')
            // 'password' => $request->input( 'no_hp' ),
        )->first();

        if (!empty($e_user)) {
            $e_user->update([
                // 'uid'     => $request->input( 'uid' ),
                'email'    => $request->input('email'),
                'name'    => $request->input('name'),
                'nik' => $request->input('nik'),
                'no_hp' => $request->input('no_hp'),
            ]);
            // $user->assignRole( 'User' );
            // $user->roles()->sync( 2 );

            $snap = new stdClass();
            $snap->data = 'success update';
            return response()->json($snap);
        } else {

            $snap = new stdClass();
            $snap->data = 'email tidak terdaftar';
            return response()->json($snap);
        }
    }

    public function profile()
    {
        $request = $_GET['uid'];
        $user = User::with('roles')->where(
            'uid',
            $request
            // 'password' => $request->input( 'no_hp' ),
        )->first();
        // $user->assignRole( 'User' );
        // $user->roles()->sync( 2 );

        $snap = new stdClass();
        $snap->data = $user;
        return response()->json($snap);
    }

    public function transaksi()
    {
        $request = $_GET['uid'];
        $user = User::where(
            'uid',
            $request
            // 'password' => $request->input( 'no_hp' ),
        )->first();
        // dd( $user );
        // $transaksis = Transaksi::with(['peserta', 'tikets', 'event', 'created_by'])->get();
        $transaksis = Transaksi::with(['peserta', 'tikets', 'event'])->where('peserta_id', $user->id)->get();
        $Transaksi = Transaksi::with(['peserta', 'tikets', 'event', 'created_by'])->where(
            'peserta_id',
            $user->id
            // 'password' => $request->input( 'no_hp' ),
        )->get();
        // dd( $Transaksi );
        // $user->assignRole( 'User' );
        // $user->roles()->sync( 2 );

        $snap = new stdClass();
        $snap->data = $transaksis;
        return response()->json($snap);
    }

    public function tiket()
    {
        $request = $_GET['uid'];
        $user = User::where(
            'uid',
            $request
        )->first();

        if (empty($user)) {
            $snap = new stdClass();
            $snap->data = 'user tidak terdaftar';
            return response(json_encode($snap), Response::HTTP_FORBIDDEN);
        }

        $transaksi = Transaksi::where(
            'peserta_id',
            $user->id
        )->where('status', 'success')->get();

        // dd( $transaksi );
        $tiket = array();

        foreach ($transaksi as $value) {
            foreach ($value->tikets as $d) {
                $t = Tiket::with('event')->find($d->id);
                $tiket[] = $t;
            }
            // $value->tiket = Tiket::where(
            //     'id',  $value->tiket_id
            // )->first();
            // $tiket->data = $user;
        }

        // $tiket = Tiket::where(
        //     ['peserta_id',  $user->id,
        //     'status_payment', 'success']
        // )->get();


        // $user->assignRole( 'User' );
        // $user->roles()->sync( 2 );

        $snap = new stdClass();
        $snap->data = $tiket;
        return response()->json($snap);
    }

    public function beliApi(Request $request)
    {

        $data = $request->all();
        $rules = [
            'uid' => 'required', //Must be a number and length of value is 8
            // 'email' => 'required',
            // 'no_hp' => 'required',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            //TODO Handle your data

            // dd( $request->input( 'id' ) );
            $data = $request->all();

            // dd( $data );
            $length = 10;
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
            }

            $no_invoice = 'TRX-' . Str::upper($random);

            // if ( $request->input( 'nama' ) == '' || $request->input( 'email' ) == '' || $request->input( 'no_hp' ) == '' || $request->input( 'nik' ) ) {
            //     $events = Event::pluck( 'nama_event', 'id' )->prepend( trans( 'global.pleaseSelect' ), '' );
            //     $no_t = Tiket::orderBy( 'no_tiket', 'DESC' )->first();
            //     $data = $request->all();
            //     $data[ 'price_1' ]  = $data[ 'day_1' ] * 210000;
            //     $data[ 'price_2' ]  = $data[ 'day_2' ] * 210000;
            //     $data[ 'price_3' ]  = $data[ 'day_3' ] * 280000;
            //     return view( 'daftar', compact( 'events', 'no_t', 'data' ) );
            // } else {

            $tiket_id = array();
            $amount = 0;
            $code = uniqid() . uniqid();
            // if ( isset( $request->input( 'id' )[ 1 ] ) ) {
            foreach ($request->input('id') as $d) {

                // $u1 = ( int )$request->input( 'id' )[ 1 ];

                // for ( $u = 0; $u<$u1; $u++ ) {
                // $no_tiket = '0' . ( int )Tiket::orderBy( 'no_tiket', 'DESC' )->first()->no_tiket + 1;
                $nb = Tiket::orderBy('id', 'DESC')->first()->id;
                $no_tiket = $nb  + 1;
                // var_dump( $no_tiket );
                $tiket_id[] = $no_tiket;
                // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
                $total_bayar = Event::find($d)->harga;
                $amount += $total_bayar;

                $userdata = User::where('uid', $request->input('uid'))->first();

                if (null !== $userdata->nik) {
                    $nik = $userdata->nik;
                } else {
                    $nik = 'generate';
                }

                if (null !== $userdata->no_hp) {
                    $no_hp = $userdata->no_hp;
                } else {
                    $no_hp = 'generate';
                }

                if (null !== $userdata->email) {
                    $email = $userdata->email;
                } else {
                    $email = 'generate';
                }

                if (null !== $userdata->name) {
                    $nama = $userdata->name;
                } else {
                    $nama = 'generate';
                }

                // $pendaftar = Tiket::create( array_merge( $request->all(), [
                //     'nama' => $nama,
                //     'email' => $email,
                //     'no_hp' => $no_hp,
                //     'no_tiket' => $no_tiket,
                //     // 'total_bayar' => $total_bayar,
                //     'event_id' => $d,
                //     'nik' => $request->input( 'uid' ),
                //     // 'status_payment' => 'pending',
                // ] ) );

                // $uid = User::where(
                //     'uid', $request->input( 'uid' ) )->first();

                $pendaftar = Tiket::create(array_merge($request->all(), [
                    'nama' => $nama,
                    'email' => $email,
                    'no_hp' => $no_hp,
                    'no_tiket' => $no_tiket,
                    'total_bayar' => $total_bayar,
                    'event_id' => $d,
                    'peserta_id' => $userdata->id,
                    'status_payment' => 'pending',
                    'qr' => $code,
                ]));

                QrCode::format('png')->size(300)->generate($code, '../public/qrcodes/' . $no_tiket . '.png');

                if ($media = $request->input('ck-media', false)) {
                    Media::whereIn('id', $media)->update(['model_id' => $pendaftar->id]);
                }

                // echo $d;

                // }
            }

            // var_dump( $tiket_id );

            // var_dump(serialize( $no_tiket ));
            $transaksi = Transaksi::create([
                'invoice'       => $no_invoice,
                // 'events'   => serialize( $no_tiket ),
                'peserta_id'    => $userdata->id,
                'amount'        => $amount,
                'note'          => $userdata->name,
                'status'        => 'pending',
                'uid'        => $userdata->uid,
            ]);
            $transaksi->tikets()->sync($tiket_id);

            $payload = [
                'transaction_details' => [
                    'order_id'      => $transaksi->invoice,
                    'gross_amount'  => $transaksi->amount,
                ],
                'customer_details' => [
                    'first_name'       => $userdata->name,
                    'email'            => $userdata->email,
                ]
            ];

            $paymentUrl = Snap::createTransaction($payload)->redirect_url;
            $snap = new stdClass();
            $snap->data = $paymentUrl;
            // $snap->status = 'success';
            // $snap->message = $transaksi->amount;
            return response()->json($snap);

            // die;
            // if ( isset( $request->input( 'id' )[ 2 ] ) ) {
            //     $u2 = ( int )$request->input( 'id' )[ 2 ];
            //     for ( $u = 0; $u<$u2; $u++ ) {
            //         $no_tiket = '0' . ( int )Tiket::orderBy( 'no_tiket', 'DESC' )->first()->no_tiket + 1;
            //         $tiket_id[] = $no_tiket;
            //         // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
            //         $total_bayar = Event::find( 2 )->harga;
            //         $amount += $total_bayar;
            //         $pendaftar = Tiket::create( array_merge( $request->all(), [
            //             'no_tiket' => $no_tiket,
            //             'event_id' => 2,
            //             'total_bayar' => $total_bayar,
            //             'nik' => $no_tiket,
            //             'status_payment' => 'pending',
            // ] ) );
            //         if ( $media = $request->input( 'ck-media', false ) ) {
            //             Media::whereIn( 'id', $media )->update( [ 'model_id' => $pendaftar->id ] );
            //         }
            //     }
            // }

            // if ( isset( $request->input( 'id' )[ 3 ] ) ) {
            //     $u3 = ( int )$request->input( 'id' )[ 3 ];
            //     for ( $u = 0; $u<$u3; $u++ ) {
            //         $no_tiket = '0' . ( int )Tiket::orderBy( 'no_tiket', 'DESC' )->first()->no_tiket + 1;
            //         $tiket_id[] = $no_tiket;
            //         // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
            //         $total_bayar = Event::find( 3 )->harga;
            //         $amount += $total_bayar;
            //         $pendaftar = Tiket::create( array_merge( $request->all(), [
            //             'no_tiket' => $no_tiket,
            //             'event_id' => 3,
            //             'total_bayar' => $total_bayar,
            //             'nik' => $no_tiket,
            //             'status_payment' => 'pending',
            // ] ) );
            //         if ( $media = $request->input( 'ck-media', false ) ) {
            //             Media::whereIn( 'id', $media )->update( [ 'model_id' => $pendaftar->id ] );
            //         }
            //     }
            // }

            // if ( isset( $request->input( 'id' )[ 4 ] ) ) {
            //     $u4 = ( int )$request->input( 'id' )[ 4 ];
            //     for ( $u = 0; $u<$u4; $u++ ) {
            //         $no_tiket = '0' . ( int )Tiket::orderBy( 'no_tiket', 'DESC' )->first()->no_tiket + 1;
            //         $tiket_id[] = $no_tiket;
            //         // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
            //         $total_bayar = Event::find( 4 )->harga;
            //         $amount += $total_bayar;
            //         $pendaftar = Tiket::create( array_merge( $request->all(), [
            //             'no_tiket' => $no_tiket,
            //             'event_id' => 4,
            //             'total_bayar' => $total_bayar,
            //             'nik' => $no_tiket,
            //             'status_payment' => 'pending',
            // ] ) );
            //         if ( $media = $request->input( 'ck-media', false ) ) {
            //             Media::whereIn( 'id', $media )->update( [ 'model_id' => $pendaftar->id ] );
            //         }
            //     }
            // }

            // if ( isset( $request->input( 'id' )[ 5 ] ) ) {
            //     $u5 = ( int )$request->input( 'id' )[ 5 ];
            //     for ( $u = 0; $u<$u5; $u++ ) {
            //         $no_tiket = '0' . ( int )Tiket::orderBy( 'no_tiket', 'DESC' )->first()->no_tiket + 1;
            //         $tiket_id[] = $no_tiket;
            //         // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
            //         $total_bayar = Event::find( 5 )->harga;
            //         $amount += $total_bayar;
            //         $pendaftar = Tiket::create( array_merge( $request->all(), [
            //             'no_tiket' => $no_tiket,
            //             'event_id' => 5,
            //             'total_bayar' => $total_bayar,
            //             'nik' => $no_tiket,
            //             'status_payment' => 'pending',
            // ] ) );
            //         if ( $media = $request->input( 'ck-media', false ) ) {
            //             Media::whereIn( 'id', $media )->update( [ 'model_id' => $pendaftar->id ] );
            //         }
            //     }
            // }

            // if ( isset( $request->input( 'id' )[ 6 ] ) ) {
            //     $u6 = ( int )$request->input( 'id' )[ 6 ];
            //     for ( $u = 0; $u<$u6; $u++ ) {
            //         $no_tiket = '0' . ( int )Tiket::orderBy( 'no_tiket', 'DESC' )->first()->no_tiket + 1;
            //         $tiket_id[] = $no_tiket;
            //         // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
            //         $total_bayar = Event::find( 6 )->harga;
            //         $amount += $total_bayar;
            //         $pendaftar = Tiket::create( array_merge( $request->all(), [
            //             'no_tiket' => $no_tiket,
            //             'event_id' => 6,
            //             'total_bayar' => $total_bayar,
            //             'nik' => $no_tiket,
            //             'status_payment' => 'pending',
            // ] ) );
            //         if ( $media = $request->input( 'ck-media', false ) ) {
            //             Media::whereIn( 'id', $media )->update( [ 'model_id' => $pendaftar->id ] );
            //         }
            //     }
            // }

            // $u2 = ( int )$request->input( 'day_2' );
            // for ( $u = 0; $u<$u2; $u++ ) {
            //     $no_tiket = '0' . Tiket::orderBy( 'no_tiket', 'DESC' )->first()->no_tiket + 1;
            //     $tiket_id[] = $no_tiket;
            //     // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
            //     $total_bayar = Event::find( 2 )->harga;
            //     $pendaftar = Tiket::create( array_merge( $request->all(), [
            //         'no_tiket' => $no_tiket,
            //         'total_bayar' => $total_bayar,
            //         // 'token' => $request->input( '_token' ),
            //         'status_payment' => 'pending',
            // ] ) );
            //     if ( $media = $request->input( 'ck-media', false ) ) {
            //         Media::whereIn( 'id', $media )->update( [ 'model_id' => $pendaftar->id ] );
            //     }

            // }

            // $u3 = ( int )$request->input( 'day_3' );
            // for ( $u = 0; $u<$u3; $u++ ) {
            //     $no_tiket = '0' . Tiket::orderBy( 'no_tiket', 'DESC' )->first()->no_tiket + 1;
            //     $tiket_id[] = $no_tiket;
            //     // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
            //     $total_bayar = Event::find( 3 )->harga;
            //     $pendaftar = Tiket::create( array_merge( $request->all(), [
            //         'no_tiket' => $no_tiket,
            //         'total_bayar' => $total_bayar,
            //         // 'token' => $request->input( '_token' ),
            //         'status_payment' => 'pending',
            // ] ) );
            //     if ( $media = $request->input( 'ck-media', false ) ) {
            //         Media::whereIn( 'id', $media )->update( [ 'model_id' => $pendaftar->id ] );
            //     }

            // }

            // $data = $request->all();
            // $data[ 'price_1' ]  = $data[ 'day_1' ] * 210000;
            // $data[ 'price_2' ]  = $data[ 'day_2' ] * 210000;
            // $data[ 'price_3' ]  = $data[ 'day_3' ] * 280000;

            // $total_bayar = $data[ 'price_1' ] + $data[ 'price_2' ] + $data[ 'price_3' ];

            //create snap token
            // $snapToken = Snap::getSnapToken( $payload );
            // $transaksi->snap_token = $snapToken;
            // $transaksi->save();

            // $snap = new stdClass();
            // $snap->data = $snapToken;

            // return ( $snap )
            //     ->response()
            //     ->setStatusCode( Response::HTTP_ACCEPTED );

            // return json_encode( $snap );

            // dd( $snap );
            // echo "       
            // <html>
            // <head>
            // <meta name='viewport' content='width=device-width, initial-scale=1'>
            // <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
            // <!-- <script type='text/javascript'
            //     src='https://app.sandbox.midtrans.com/snap/snap.js'
            //     data-client-key='SB-Mid-client-pbCU77GhpobR9an-'></script>-->
            //     <script type='text/javascript'
            //     src='https://app.sandbox.midtrans.com/snap/snap.js'
            //     data-client-key='SB-Mid-client-D9HPUKW3PBWyP6q3-'></script>
            // <!-- Note: replace with src='https://app.midtrans.com/snap/snap.js' for Production environment -->
            // </head>
            // <script type='text/javascript'>
            // window.onload = function() {
            //     window.snap.pay('" . $snapToken . "');
            //   };
            //   </script>
            // <body>
            // <!-- <button id='pay-button'>Pay!</button> -->
            // <script type='text/javascript'>
            //     // For example trigger on button clicked, or any time you need
            //     var payButton = document.getElementById('pay-button');
            //     payButton.addEventListener('click', function () {
            //     // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            //     window.snap.pay('" . $snapToken . "');
            //     // customer will be redirected after completing payment pop-up
            //     });
            // </script>
            // </body>
            // </html>";
            //  return view( 'bayar', compact( 'snap' ) );
            // }
            // return redirect()->route( 'admin.pendaftars.index' );
        } else {
            //TODO Handle your error
            return response()->json(['data' => $validator->errors()->all()]);
        }
    }

    public function notificationHandler(Request $request)
    {
        $payload      = $request->getContent();
        $notification = json_decode($payload);

        $validSignatureKey = hash('sha512', $notification->order_id . $notification->status_code . $notification->gross_amount . config('services.midtrans.serverKey'));

        if ($notification->signature_key != $validSignatureKey) {
            return response(['message' => 'Invalid signature'], 403);
        }

        $transaction  = $notification->transaction_status;
        $type         = $notification->payment_type;
        $orderId      = $notification->order_id;
        $fraud        = $notification->fraud_status;

        //data donation
        $data_donation = Transaksi::where('invoice', $orderId)->first();

        if ($transaction == 'capture') {

            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {

                if ($fraud == 'challenge') {

                    /**
                     *   update invoice to pending
                     */
                    $data_donation->update([
                        'status' => 'pending'
                    ]);
                } else {

                    /**
                     *   update invoice to success
                     */
                    $data_donation->update([
                        'status' => 'success'
                    ]);
                }
            }
        } elseif ($transaction == 'settlement') {

            /**
             *   update invoice to success
             */
            $data_donation->update([
                'status' => 'success'
            ]);
        } elseif ($transaction == 'pending') {

            /**
             *   update invoice to pending
             */
            $data_donation->update([
                'status' => 'pending'
            ]);
        } elseif ($transaction == 'deny') {

            /**
             *   update invoice to failed
             */
            $data_donation->update([
                'status' => 'failed'
            ]);
        } elseif ($transaction == 'expire') {

            /**
             *   update invoice to expired
             */
            $data_donation->update([
                'status' => 'expired'
            ]);
        } elseif ($transaction == 'cancel') {

            /**
             *   update invoice to failed
             */
            $data_donation->update([
                'status' => 'failed'
            ]);
        }
    }

    public function store(StorePendaftarRequest $request)
    {
        $u = (int)$request->input('total_bayar');

        for (
            $u = 0;
            $u < $request->input('total_bayar');
            $u++
        ) {
            $no_tiket = '0' . Tiket::orderBy('no_tiket', 'DESC')->first()->no_tiket + 1;
            // $pendaftar->no_tiket = '0' . Tiket::latest()->first()->nama;
            $total_bayar = Event::find($request->input('event_id'))->harga;
            $pendaftar = Tiket::create(array_merge($request->all(), [
                'no_tiket' => $no_tiket,
                'total_bayar' => $total_bayar,
            ]));
            if ($media = $request->input('ck-media', false)) {
                Media::whereIn('id', $media)->update(['model_id' => $pendaftar->id]);
            }
        }

        return redirect()->route('admin.pendaftars.index');
    }

    public function edit(Pendaftar $pendaftar)
    {
        abort_if(Gate::denies('pendaftar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = Event::pluck('nama_event', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pendaftar->load('event');

        return view('admin.pendaftars.edit', compact('events', 'pendaftar'));
    }

    public function update(UpdatePendaftarRequest $request, Pendaftar $pendaftar)
    {
        $pendaftar->update($request->all());

        return redirect()->route('admin.pendaftars.index');
    }

    public function show(Pendaftar $pendaftar)
    {
        abort_if(Gate::denies('pendaftar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pendaftar->load('event');

        return view('admin.pendaftars.show', compact('pendaftar'));
    }

    public function destroy(Pendaftar $pendaftar)
    {
        abort_if(Gate::denies('pendaftar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pendaftar->delete();

        return back();
    }

    public function massDestroy(MassDestroyPendaftarRequest $request)
    {
        Tiket::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('pendaftar_create') && Gate::denies('pendaftar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Pendaftar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
