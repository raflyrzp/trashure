<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    use HasFactory;

    protected $table = 'point_histories';

    protected $fillable = [
        'report_id',
        'user_id',
        'points',
        'granted_at',
    ];

    protected $casts = [
        'points' => 'integer',
        'granted_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
