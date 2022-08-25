<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdraw extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public const STATUS_SELECT = [
        'progress' => 'progress',
        'done'     => 'done',
        'cancel'   => 'cancel',
    ];

    public $table = 'withdraws';

    protected $dates = [
        'tanggal_withdraw',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'kode_withdraw',
        'tanggal_withdraw',
        'jumlah_withdraw',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getTanggalWithdrawAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setTanggalWithdrawAttribute($value)
    {
        $this->attributes['tanggal_withdraw'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
