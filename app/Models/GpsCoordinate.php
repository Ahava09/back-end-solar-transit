<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsCoordinate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'date_time',
    ];

    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
