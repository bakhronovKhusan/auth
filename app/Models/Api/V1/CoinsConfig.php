<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Model;


class CoinsConfig extends Model
{

    protected $table = 'coins_config';

    protected $fillable = [
        'coins_type_id',
        'code',
        'description',
        'point_plus',
        'point_minus',
        'order_number',
        'is_show',
    ];

    public function coins_type()
    {
        return $this->belongsTo(CoinsType::class);
    }


}
