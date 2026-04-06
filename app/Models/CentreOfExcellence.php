<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentreOfExcellence extends Model
{
    
    protected $table = 'centre_of_excellences';

    public function keyHighlights(){
        return $this->hasMany(KeyHighlight::class, 'coe_id', 'id');
    }

    public function testimonials(){
        return $this->belongsToMany(Testimonial::class, 'coe_testimonial', 'coe_id', 'testimonial_id');
    }

    public function conditions(){
        return $this->hasMany(Condition::class, 'coe_id', 'id');
    }

    public function branch(){
        return $this->belongsToMany(Branch::class, 'branch_coe', 'coe_id', 'branch_id');
    }

    public function specialties(){
        return $this->belongsToMany(Speciality::class, 'coe_speciality', 'coe_id', 'speciality_id');
    }

}
