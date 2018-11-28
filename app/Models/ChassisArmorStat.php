<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChassisArmorStat extends Model
{
    protected $fillable = [
        'chassis_id',
        'armor',
        'move',
        'cost',
    ];

    public $timestamps = false;

    public function chassis()
    {
        return $this->belongsTo(Chassis::class);
    }
}
