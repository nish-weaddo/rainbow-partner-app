<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    
    public function branch(){
        return $this->hasMany(Branch::class, 'region_id', 'id');
    }

    public function regionBranches(){
      return $this->hasMany(Branch::class);
    } 
    

}
