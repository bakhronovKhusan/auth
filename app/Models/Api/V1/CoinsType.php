<?php
namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Model;


class CoinsType extends Model{

    protected $table = 'coins_type';

    protected $fillable = [
        // 'title_uz',
        'type_name',
        'icon',
        'updated',
        'notes',
        'important',
        'important_text',
        'order_number',
    ];

    public function configs(){
        return $this->hasMany(CoinsConfig::class,'coins_type_id','id')->orderBy('order_number');
    }

}
