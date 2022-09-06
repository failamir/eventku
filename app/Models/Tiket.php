<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Tiket extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use Auditable;
    use HasFactory;

    public $timestamps = true;

    public const STATUS_SELECT = [
        'valid'   => 'valid',
        'unvalid' => 'unvalid',
        'assign' => 'assign',
    ];

    public const CHECKIN_SELECT = [
        'sudah'    => 'sudah',
        'belum'    => 'belum',
        'terpakai' => 'terpakai',
    ];

    public const TYPE_PAYMENT_SELECT = [
        'CASH'     => 'cash',
        'Cash'     => 'cash',
        'cash'     => 'cash',
        'transfer' => 'transfer',
        'Transfer' => 'transfer',
        'Transfer ' => 'transfer',
        ' Transfer ' => 'transfer',
        'TRANSFER' => 'transfer',
        'Tranfer' => 'transfer',
        'qris'     => 'qris',
        'Free'     => 'Free',
        'FREE'     => 'Free',
    ];

    public const STATUS_PAYMENT_SELECT = [
        'Success' => 'success',
        'SUCCESS' => 'success',
        'Succsess' => 'success',
        'success' => 'success',
        'pending' => 'pending',
        'Pending' => 'pending',
        'PENDING' => 'pending',
        'cancel'  => 'cancel',
        'refund'  => 'refund',
        'Free'     => 'Free',
        'FREE'     => 'Free',
    ];

    public $table = 'tikets';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'no_tiket',
        'peserta_id',
        'checkin',
        'notes',
        'qr',
        'status',
        'status_payment',
        'type_payment',
        'no_hp',
        'nama',
        'nik',
        'email',
        'event_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'pic_checkin',
        'pic_checkout',
        'pic_assign',
        'invoice',
        'total_bayar',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function tiketTransaksis()
    {
        return $this->belongsToMany(Transaksi::class);
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
