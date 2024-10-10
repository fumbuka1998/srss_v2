<?php

namespace App\Http\Controllers\configurations\general;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;

class GeneralController extends Controller
{

    public function index(){

        // $profile = SchoolProfile::get();
         $profile = SchoolProfile::get()->first();

        $imageUrl = '';
        if ($profile->school_logo) {
            $imageUrl = asset('storage/' . $profile->school_logo);
        }

        $data = [
            'imageUrl'=>$imageUrl,
            'profile'=>$profile
        ];


        return view('configurations.general.index')->with($data);

    }


    public function updateSchoolLogo(Request $req, $school_id)
    {
    //   return $req;
        $school = SchoolProfile::where('id', $school_id)->first();

        if ($req->file('file')) {
            $rules = [
                'file' => 'required|image|max:2048',
                // 'file' => 'required|image|mimes:jpeg,png,jpg,gif|dimensions:max_width=132,max_height=185|max:30',
            ];

            $validator = \Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $mime_type = $req->file('file')->getClientMimeType();

            if (substr($mime_type, 0, 5) == "image") {
                if ($req->hasfile('file')) {
                    $avatar_name = $req->file('file')->getClientOriginalName();
                    $path = $req->file('file')->storeAs('school/' . $school->id . '/' . 'school_logo', $avatar_name, 'public');
                    $school->update(['school_logo' => $path]);
                }
            }
        }

        // return response()->json(['success' => true]);
        $data = ['state' => 'Done', 'title' => 'Successful', 'msg' => 'Image edited successful'];

        return response($data);
    }

    public function profile(Request $request){
        $data = $request->all()['data'];
        // dd($data);
       $schoolProfile = SchoolProfile::findorfail(1);
        $schoolProfile->update($data);

        if($schoolProfile){
            return response()->json(['message' => 'Profile Created successfully', 'data' => $schoolProfile]);
        }

        return response()->json(['message' => 'Profile Not Created']);


    }
}
