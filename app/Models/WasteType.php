<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteType extends Model
{
    use HasFactory;

    protected $table = 'waste_types';

    protected $fillable = [
        'name',
        'description',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class, 'waste_type_id');
    }
}
