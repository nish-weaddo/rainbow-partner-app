<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Searchable\SearchResult;
use Spatie\Searchable\Searchable;

class User extends Authenticatable implements Searchable
{
    use  HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getSearchResult(): SearchResult
    {
        
       
            $user_type = $this->user_type;


            if($this->user_type === 'Doctor')
            {
                $slugRegions = RegionUser::where('user_id',$this->id)->first();
                if($slugRegions !== null)
                {
                    $slugRegion = Region::where('id',$slugRegions->region_id)->first();
                $slugBranches = BranchDoctorOrderNumber::where('doctor_id',$this->id)->orderBy('order_number','desc')->first();
                $slugBranch = Branch::where('id',$slugBranches->branch_id)->first();
                $slugSpecialities = CoeUser::where('user_id',$this->id)->first();
                $slugSpeciality = CentreOfExcellence::where('id',$slugSpecialities->coe_id)->first();
                $url = route('doctor-profile.show', [$slugRegion->slug,$slugBranch->slug,$slugSpeciality->slug,$this->slug]);
                $description = $this->short_info;
                $title = [];

       $title[0] = $this->name;

       

       $costomData = $this->status;
       $title[1] = $costomData;

       $title = implode(',',$title);
                

                return new \Spatie\Searchable\SearchResult(
                    $this,
                    $title,
                    $url,
                    $description
                 );
                }
                else
                {
                    return new \Spatie\Searchable\SearchResult(
                        $this,
                        $this->name = '',
                        $url = 'user'
                     );  
                }
                
            }
            

            else {
               

            return new \Spatie\Searchable\SearchResult(
                $this,
                $this->name = '',
                $url = 'user'
             );
            }

            
        
       

        
    }


    public function branch(){
        return $this->belongsToMany(Branch::class)
                    ->withPivot('user_id', 'branch_id');
        
    }

    public function centre_of_excellence(){
        return $this->belongsToMany(Coe::class)->as('coe')
            ->withPivot('user_id', 'coe_id');
    }

    public function region(){
        return $this->belongsToMany(Region::class)
            ->withPivot('user_id', 'region_id');
        
    }
}
