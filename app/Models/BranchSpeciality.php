<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchSpeciality extends Model
{
    
    protected $table = 'branch_speciality';

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function speciality(){
        return $this->belongsTo(Speciality::class, 'speciality_id', 'id');
    }

}
