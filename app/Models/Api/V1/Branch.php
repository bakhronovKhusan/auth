<?php
namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Branch extends Model{
    use Userstamps;

    protected $fillable = [
        'name', 'phone', 'address', 'location', 'website', 'email', 'facebook', 'telegram', 'instagram', 'youtube', 'bank_name', 'bank_account', 'bank_code', 'inn', 'company_id', 'max_times'
    ];

}
