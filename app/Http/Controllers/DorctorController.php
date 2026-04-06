<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Region;
use App\Models\Branch;
use App\Models\BranchDoctorOrderNumber;
use App\Models\CentreOfExcellence;
use App\Models\BranchSpeciality;
use App\Models\Speciality;
use App\Models\Patient;
use App\Models\Sms;
use App\Models\Whatsapp;
use DB;
use Carbon\Carbon;

class DorctorController extends Controller
{

    protected $patient;
    protected $sms;
    protected $Whatsapp;
    public function __construct(Patient $patient, Sms $sms, Whatsapp $Whatsapp){
        $this->patient = $patient;
        $this->sms = $sms;
        $this->Whatsapp = $Whatsapp;
    }
    
    public function allDoctors(Request $request, $region_name, $branch_name){
            $branch = Branch::where('slug', $branch_name)->where('status', 'active')->first();

            if (empty($branch)) {
                return view('errors.404');
            }

            $region = Region::where('slug', $region_name)->first();
            if (empty($region)) {
                return view('errors.404');
            }

            $filteredDoctorIds = [];

            if ($request->speciality) {
                $specialityDoctorIds = DB::table('speciality_user')
                        ->where('speciality_id', $request->speciality)
                        ->pluck('user_id')
                        ->toArray();

                $filteredDoctorIds = $specialityDoctorIds;   
            }
            
            if($request->doctor){
                $doctorId = DB::table('users')
                        ->where('slug', $request->doctor)
                        ->value('id');

                if($doctorId){
                    if(!empty($filteredDoctorIds)){
                        $filteredDoctorIds = array_intersect($filteredDoctorIds, [$doctorId]);
                    }else{
                        $filteredDoctorIds = [$doctorId];
                    }
                }
            }

            // $doctorRecords = BranchDoctorOrderNumber::orderBy('order_number', 'ASC')
            //     ->where('branch_id', $branch->id)
            //     ->get(['doctor_id', 'order_number']);
            $doctorRecords = BranchDoctorOrderNumber::orderBy('order_number', 'ASC')
                ->where('branch_id', $branch->id);

            if (!empty($filteredDoctorIds)) {
                $doctorRecords->whereIn('doctor_id', $filteredDoctorIds);
            }
            $doctorRecords = $doctorRecords->get(['doctor_id', 'order_number']);

            $i = 1;
            $doctors = [];
            
            if (count($doctorRecords) > 0) {
                foreach ($doctorRecords as $record) {

                    // if($i>18){
                    //     break;
                    // }
                    $doc_id = $record->doctor_id;
                    $order_number = $record->order_number;

                    $locationU = "";
                    $userData = DB::table('users')->select('id', 'name', 'designation', 'canonical', 'small_image', 'meta_description', 'languages', 'experience', 'position', 'slug')
                        ->where('id', $doc_id)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->first();

                    if (isset($userData->id)) {
                        $locationArray = array();
                        $usersLocationDetails = DB::table('branches')
                            ->join('branch_user', 'branches.id', '=', 'branch_user.branch_id')
                            ->select('name', 'schema_telephone', 'address', 'schema_postal_code')
                            ->where('user_id', $userData->id)
                            ->get();

                        if (count($usersLocationDetails) > 0) {
                            foreach ($usersLocationDetails as $loc) {
                                $locationArray[] = $loc->name;
                            }
                            $locationU = implode(", ", $locationArray);

                            $branchName = $usersLocationDetails[0]->name;
                            $branchContact = $usersLocationDetails[0]->schema_telephone;
                            $branchAddress = $usersLocationDetails[0]->address;
                            $brachPostalCode = $usersLocationDetails[0]->schema_postal_code;
                        }

                        $doctors[] = array(
                            "doc_id" => $userData->id,
                            "slug" => $userData->slug,
                            "name" => $userData->name,
                            "url" => $userData->canonical,
                            "languages" => $userData->languages,
                            "designation" => $userData->designation,
                            "small_image" => $userData->small_image,
                            "branches" => $locationU,
                            "description" => $userData->designation,
                            "branchName" => $branchName,
                            "branchContact" => $branchContact,
                            "branchAddress" => $branchAddress,
                            "brachPostalCode" => $brachPostalCode,
                            "experience" => $userData->experience,
                            "order_number" => $order_number,
                            "position" => $order_number,
                        );
                        $i++;
                    }
                }
            }

            usort($doctors, function ($a, $b) {
                $positionComparison = $a['position'] <=> $b['position'];
                
                if ($positionComparison === 0) {
                    return strcmp($a['name'], $b['name']);
                }
                return $positionComparison;
            });


            $specialitie_list_data = [];
            
            $allBranchecwithSpecialities = Branch::where(['status'=>'active', 'id'=>$branch->id])->get();
           
            $speciality_list = [];

            $branchSpecialitiess = BranchSpeciality::where('branch_id', $branch->id)->get();
            foreach ($branchSpecialitiess as $key => $specialityId) {
                $speciality_list[] = Speciality::select('id', 'slug', 'name')
                                    ->where(['id'=>$specialityId->speciality_id, 'status'=>'active'])->first();
            }

            $doctorRecordss = BranchDoctorOrderNumber::orderBy('order_number', 'ASC')
                ->where('branch_id', $branch->id)
                ->pluck('doctor_id');
             
            $allDoctor = DB::table('users')->select('id', 'name', 'slug')
                        ->whereIn('id', $doctorRecordss)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->get();

            return view('doctors', compact('doctors', 'region', 'branch', 'speciality_list', 'allDoctor'));
            // return view('branch.units', compact('region_Slug', 'doctors'));
            // return response()->json($allBranchecwithSpecialities);
    }

    public function getSessionByDoctor(Request $request){
        $doctor = DB::table('users')->where('user_type', 'Doctor')->where('status', 'active')->where('slug', $request->name)->first();

        $selectedBranch =  Branch::where('slug', $request->branch)->with([
            'region' => function ($query) {
                $query->select('id', 'name', 'slug');
            }
        ])->first();

        $url = $request->doctor;
        $segments = explode('/', $url);
        $doctorIndex = array_search('doctor', $segments);
        $speciality = $segments[$doctorIndex + 3] ?? null;

        $selectedSpeciality =  CentreOfExcellence::where('slug', $speciality)->first();
        
        $All_Doctors = [];
        $ApiVCData = [];
        $response2 = [];
        $response1 = [];
        try{
            $post_data = json_encode(array(
                "CareproviderCode" => "500029", //$doctor->new_doctor_UID, 
                "OrganisationUID" => "4", //$selectedBranch->UID, // "4",
                "specialityIdentifier" => "28", //$selectedSpeciality->UID, //"28",
                "AppointmentType" => "Video", //"Physical", THIS IS FOR PHYSICAL
                "noofdays" => "30",
                // "deptcode"=> "40FDPAGP"
                "deptcode"=> ""
            )); 

            $ApiData =  json_decode($this->patient->curlAuthRequest($post_data, 'GetSessionsByDoctor'));

            $post_dat_vc = json_encode(array(
                "CareproviderCode" => "500029", //$doctor->new_doctor_UID,
                "OrganisationUID" => "4", //$selectedBranch->UID,
                "specialityIdentifier" => "28", //$selectedSpeciality->UID,
                "AppointmentType" => "Video",
                "noofdays" => "30",
                // "deptcode"=> "40FDPAGP"
                "deptcode"=> ""  
            ));

            $ApiVCData_session =  json_decode($this->patient->curlAuthRequest($post_dat_vc, 'GetSessionsByDoctor'));
            if ($ApiVCData_session !== null) {
                $isFirst = true;
                foreach ($ApiVCData_session as $vc_row) {
                    if($isFirst){
                        $apptDate = Carbon::parse($vc_row->SessionDate)->format('Y-m-d');
                        $sessionId = $vc_row->SessionDefinitionUID1;

                        $post_data1 = '{
                            "SessionDefinitionUID":"'.$sessionId.'",
                            "AppointmentDate":"'.$apptDate.'",
                            "OrganisationUID":"4",
                            "AppointmentType": "Video"
                        }';

                        $response1 =$this->patient->curlAuthRequest($post_data1, 'GetSlotsBySession');
                        $response1 = json_decode($response1, true);
                        $isFirst = false;
                    }

                    if ($vc_row->isportalbookingallowed == 1) {
                        $ApiVCData[$vc_row->SessionDate] = array(
                            "SessionDate" => $vc_row->SessionDate,
                            "SlotTime1" => $vc_row->SlotTime1,
                            "SlotTime2" => $vc_row->SlotTime2,
                            "isportalbookingallowed" => $vc_row->isportalbookingallowed,
                            "SessionDefinitionUID1" => $vc_row->SessionDefinitionUID1,
                            "SessionDefinitionUID2" => $vc_row->SessionDefinitionUID2,
                            "DepCode" => $vc_row->DepCode,
                            "OrgCode1" => $vc_row->OrgCode1
                        );
                    }
                }
            }

            if ($ApiData !== null) {
                $isFirst1 = true;
                foreach ($ApiData as $pc_row) {

                    if($isFirst1){
                        $apptDate = Carbon::parse($pc_row->SessionDate)->format('Y-m-d');
                        $sessionId = $pc_row->SessionDefinitionUID1;
                        $sessionId1 = $pc_row->SessionDefinitionUID2;

                        $post_data2 = '{
                            "SessionDefinitionUID":"'.$sessionId.'",
                            "AppointmentDate":"'.$apptDate.'",
                            "OrganisationUID":"4",
                            "AppointmentType": "Physical"
                        }';

                        $response2 =$this->patient->curlAuthRequest($post_data2, 'GetSlotsBySession');
                        $response2 = json_decode($response2, true);
                        $isFirst1 = false;
                    }

                    if ($pc_row->isportalbookingallowed == 1) {
                        $All_Doctors[$pc_row->SessionDate] = array(
                            "SessionDate" => $pc_row->SessionDate,
                            "SlotTime1" => $pc_row->SlotTime1,
                            "SlotTime2" => $pc_row->SlotTime2,
                            "isportalbookingallowed" => $pc_row->isportalbookingallowed,
                            "SessionDefinitionUID1" => $pc_row->SessionDefinitionUID1,
                            "SessionDefinitionUID2" => $pc_row->SessionDefinitionUID2,
                            "DepCode" => $pc_row->DepCode,
                            "OrgCode1" => $pc_row->OrgCode1
                        );
                    }
                }
            }

            return response()->json([
                'physical' => view('physical-slots', compact('All_Doctors', 'response2', 'selectedBranch'))->render(),
                'video' => view('video-slots', compact('ApiVCData', 'response1', 'selectedBranch'))->render()
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getslotsBySession(Request $request){
        // $post_data =  '{"SessionDefinitionUID":"'.$request->input('SessionDefinitionUID').'",
        //     "AppointmentDate":"'.$request->input('AppointmentDate').'",
        //     "OrganisationUID":"'.$request->input('OrganisationUID').'",
        //     "AppointmentType": "Physical"
        //     }';
        $apptDate = $request->apptDate;
        $sessionId = $request->sessionId;
        $type = $request->type;
        // $orgUID = $request->orgUID;
        $orgUID = '4';
        $post_data =  '{"SessionDefinitionUID":"'.$sessionId.'",
            "AppointmentDate":"'.$apptDate.'",
            "OrganisationUID":"'.$orgUID.'",
            "AppointmentType": "'.$type.'"
            }';

        $response = $this->patient->curlAuthRequest($post_data, 'GetSlotsBySession'); 
        $response = json_decode($response, true);    
        return $response;        
    }


}
