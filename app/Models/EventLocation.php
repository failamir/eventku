<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLocation extends Model
{
    use HasFactory;

    public $table = 'event_location';

    protected $fillable = [
        'location',
        'link',
        'image',
    ];

    public function location()
    {
        return $this->belongsTo(Event::class, 'location_id');
    }
}
