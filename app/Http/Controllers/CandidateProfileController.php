<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandidateProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CandidateProfileController extends Controller
{
   public function profile_save(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
            'pimage'=>'required',
        ]);
        $imageName = Str::random(32).".".$request->pimage->getClientOriginalExtension();
     
        $candidate = CandidateProfile::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'pimage'=>$imageName,
        ]);
        // Save Image in Storage folder
        Storage::disk('public')->put($imageName, file_get_contents($request->pimage));
        return response()->json([
             'message' => "CandidateProfile successfully created."
        ],200);
            
    }

    public function profile_list(Request $request){
        $candidates = CandidateProfile::all();
        foreach($candidates as $candidate){
            $url = Storage::url($candidate->pimage);
            $candidate->imgpath = 'http://127.0.0.1:8000'.''.$url;
        }
        return response([
            'candidate'=>$candidates
        ], 200);
    }
    public function particular_profile_list($id){
        $candidates =  CandidateProfile::find($id);
        return response([
            'candidate'=>$candidates
        ], 200);
    }
    public function update_profile_list(Request $request, $id){
        $student = CandidateProfile::find($id);
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
            'pimage'=>'required',
        ]);
        $student->name = $request->name;
        $student->email = $request->email;
        $student->address = $request->address;
        $student->phone = $request->phone;
        if($request->pimage) {
            // Public storage
            $storage = Storage::disk('public');
     
             // Old iamge delete
            if($storage->exists($student->pimage))
                $storage->delete($student->pimage);
     
            // Image name
            $imageName = Str::random(32).".".$request->pimage->getClientOriginalExtension();
            $student->pimage = $imageName;
     
             // Image save in public folder
            $storage->put($imageName, file_get_contents($request->pimage));
        }
        $student->save();
        return response([
                'message'=>'Update Student Successfully',
                'status'=> 'success'
            ], 200);
    }
    public function delete_profile_list($id){
        $candidates =  CandidateProfile::find($id);
        if($candidates)
        {
            
            $storage = Storage::disk('public');
            if($storage->exists($candidates->pimage))
                $storage->delete($candidates->pimage);
            $candidates->delete();
            return response([
                'message'=>'Delete Student Successfully',
                'status'=>'success'
            ], 200);
        }else{
            return response([
                'message'=>'No Student Found',
                'status'=>'success'
            ], 200);
        }

        
        
    }
}
