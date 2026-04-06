<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    
    public function region(){
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

}
