<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    
    protected $table = 'specialities';

    public function testimonials(){
        return $this->belongsToMany(Testimonial::class, 'speciality_testimonial', 'speciality_id', 'testimonial_id');
    }

    public function centre_of_excellence(){
        return $this->belongsToMany(Coe::class)->as('coe')
            ->withPivot('speciality_id', 'coe_id');
    }
}
