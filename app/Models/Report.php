<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    public const STATUS_PENDING  = 'pending';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_RESOLVED = 'resolved';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_VERIFIED,
        self::STATUS_REJECTED,
        self::STATUS_RESOLVED,
    ];

    protected $fillable = [
        'reporter_id',
        'admin_id',
        'waste_type_id',
        'description',
        'location',
        'photo_url',
        'status',
        'reported_at',
        'verified_at',
        'admin_notes',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function wasteType()
    {
        return $this->belongsTo(WasteType::class, 'waste_type_id');
    }

    public function pointHistory()
    {
        return $this->hasOne(PointHistory::class, 'report_id');
    }

    public function isAwardable(): bool
    {
        return in_array($this->status, [self::STATUS_VERIFIED, self::STATUS_RESOLVED], true);
    }
}
