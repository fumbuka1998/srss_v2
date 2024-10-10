<?php

namespace App\Http\Controllers\user_management;

use App\Http\Controllers\Controller;
use App\Mail\SignInMail;
use App\Models\Attachment;
use App\Models\Club;
use App\Models\Contact;
use App\Models\ContactPerson;
use App\Models\House;
use App\Models\ModelHasPermission;
use App\Models\ModelHasRole;
use App\Models\Permission;
use App\Models\Religion;
use App\Models\ReligionSect;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\User;
use App\Models\UserHasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    //

    public function index(){


        $data['activeTab'] = 'users';
        $data['houses'] = House::all();
        $data['classes'] = SchoolClass::all();
        $data['streams'] = Stream::all();
        $data['religions'] = Religion::all();
        $data['religion_sects'] = ReligionSect::all();
        $data['clubs'] = Club::all();
        $data['roles'] = Role::all();

        return view('user-management.index')->with($data);

    }


    public function datatable(Request $request){


        try {


         $users = User::with('userHasRoles.roles')->orderBy('users.id','desc');

          $search = $request->get('search');

            if(!empty($search)){

              $users = $users->where('users.firstname', 'like', '%'.$search.'%')
                            ->orWhere('users.lastname', 'like', '%'.$search.'%');


            }




         return DataTables::of($users)


         ->editColumn('created_by',function($user){

             return User::find($user->created_by) ? User::find($user->created_by)->full_name : 'admin';
         })

         ->addColumn('full_name', function($user){
            // return $user;

            if ($user->profile_pic) {
                // return $user->profile_pic;
                $url= asset('storage/'.$user->profile_pic);
                $image = '
                <div class="user-avatar bg-primary">
                <div class="avatar-image" style="background-image: url('.asset('storage/'.$user->profile_pic).');"></div>
                </div>
                ';
            }else{
                $image = ' <div class="user-avatar bg-primary"> <span>'.$user->name_abbrv.'</span> </div>';
            }
            return '<span style="display:flex; text-align:center">'.$image .'<span style="margin-top: 0.4rem; margin-left: 0.5rem;"> '.$user->firstname.' '. $user->lastname.'  </span> </span>';
            // getNameAbbrvAttribute

         })

         ->addColumn('roles',function($user){

            $acl = '<div>';
            $colors = ['badge-info','badge-success','badge-secondary'];
            foreach ($user->userHasRoles as $key => $userHasrole) {
                $acl.= '<span class="badge '.$colors[$key].'" style="margin-top: 0.3rem;">'.$userHasrole->roles->name.'</span> ';
                // $acl.= '<a style="padding: 4px 5px; border-radius: 5px;  color: darkblue; border: 1px dotted rgba(0, 0, 139, 0.596);"> '.$userHasrole->roles->name.' </a> &nbsp;';
            }
            return $acl.="</div>";

         }) 
 
         ->addColumn('action',function($user){
             return '<span>
             <a style="color:#fff" href="'.route('users.management.profile',$user->uuid).'" class="btn btn-custon-four btn-info btn-sm profile"><i class="fa fa-user"></i></a>|
            <a style="color:#fff" href="'.route('users.management.registration',$user->uuid).'" class="btn btn-custon-four btn-primary btn-sm edit"><i class="fa fa-edit"></i></a>
             | <button data-uuid="'.$user->uuid.'" type="button"  class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
         </span>';

         })  

       ->rawColumns(['action','roles','full_name'])
       ->make();

        } catch (QueryException $e) {

           return $e->getMessage();

        }

     }


     public function store(Request $req){

        try {




            // return $req->all();

            DB::beginTransaction();

            $username = strtolower($req->firstname) .'.'.strtolower($req->lastname);


            if ($req->uuid) {

                $user = User::updateOrCreate(
                    [

                        'uuid' =>$req->uuid

                    ],

                    [
                    'firstname'=>$req->firstname,
                    'lastname'=>$req->lastname,
                    'phone'=>$req->phone,
                    'username'=> $username,
                    'email'=>$req->email,
                    'address'=>$req->address,
                    'uuid'=>$req->uuid,
                    'created_by'=>auth()->user()->id

                   ]);

                   DB::commit();

                   return response(['state'=>'done', 'refresh'=>true, 'msg'=> 'User Updated Successfully','title'=>'success', 'user'=>$user]);

            //        if ($user) {

            //         UserHasRoles::updateOrCreate(
            //             [
            //                 'user_id'=>$user->id,
            //                 'role_id'=>$req->role
            //             ],
            //         );

            // }
        }
        else{

            $user = User::create(
                [

                'firstname'=>$req->firstname,
                'lastname'=>$req->lastname,
                'phone'=>$req->phone,
                'username'=>$username,
                'password'=>'123456',
                'email'=>$req->email,
                'address'=>$req->address,
                'uuid'=>generateUuid(),
                'created_by'=>auth()->user()->id

               ]);

               if ($user) {

                    UserHasRoles::create(

                        [
                            'role_id'=>$req->role,
                            'user_id'=>$user->id
                        ],
                    );

                    ModelHasRole::create([

                        'role_id' => $req->role,
                        'model_type'=>User::class,
                        'model_id'=>$user->id

                    ]);
        }

        DB::commit();


        if($req->file('file')){

            $mime_type = $req->file('file')->getClientMimeType();

            if (substr($mime_type, 0, 5) == "image") {
                $avatar_name= $req->file('file')->getClientOriginalName();
                $path = $req->file('file')->storeAs('user/'.$user->id.'/'.'profile', $avatar_name, 'public',true);
                $user->update(['profile_pic'=> $path]);
                $full_path = asset('storage/').'/'.$path;
            }
        }

        // if ($req->email) {
        //     $this->successfulAction($req->email,$username);
        // }

        return response(['state'=>'done', 'msg'=> 'User Created','title'=>'success']);

               }


        } catch (QueryException $e) {

            DB::rollBack();
            return response(['state'=>'error', 'msg'=> $e->errorInfo[2],'title'=>'error']);
            // return $e->getMessage();

        }
    }


    public function storeAttachments(Request $request,$id){


        try {




            if ($request->hasfile('attachment_file')) {

                $avatar_name= $request->file('attachment_file')->getClientOriginalName();
                $path = $request->file('attachment_file')->storeAs('student/'.$id.'/attachments/'.$request->attachments.'', $avatar_name, 'public',true);
                // return asset('storage/').$path;


            // return

                DB::beginTransaction();

                $attachment = Attachment::Create(
                    [
                        'attachable_type'=>Student::class,
                        'attachable_id'=>$id,
                        'attachment_type'=>$request->attachments,
                        'path'=>$path,
                        'name'=>$avatar_name,
                        'created_by'=> auth()->user()->id
                    ]
                );
            }

                DB::commit();

            if($attachment){
                $mime_type = $request->file('attachment_file')->getClientMimeType();
                $full_path = asset('storage').'/'.$path;
                $btns = '<span> <a data-full_path="'.$full_path.'" data-mime_type="'.$mime_type.'" class="btn preview-icon btn-sm preview_doc btn-primary"> <i class="fa fa-eye"></i>  </a> </span>';
               $data = ['state'=>'Done','btns'=>$btns, 'type'=>$request->attachments, 'title'=>'Successful', 'msg'=>'Uploaded successful'];

               return response($data);

            }

            $data = ['state'=>'Fail', 'title'=>'Fail', 'msg'=>'could not be uploaded'];
              return  response($data);

          } catch (QueryException $e) {

            $data = ['state'=>'Error', 'title'=>'Database error', 'msg'=>'Something went wrong!<br />' . $e->errorInfo[2]];
            return  response($data);
          }

    }


    public function destroy_og(Request $req){

        try {
        $uuid = $req->uuid;
        $user = User::where('uuid',$uuid)->first();
        $destroy = $user->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();

        }

    }



    public function destroy(Request $request)
    {
        try {
            $uuid = $request->uuid;

            $user = User::where('uuid', $uuid)->first();

            if (!$user) {
                return response(['state' => 'error', 'msg' => 'User not found'], 404);
            }

           
            $destroy = $user->delete();

            if ($destroy) {
                return response(['state' => 'done', 'msg' => 'success']);
            }
        } catch (QueryException $e) {
            return response(['state' => 'error', 'msg' => $e->getMessage()], 500);
        }
    }

    // public function destroy(Request $request)
    // {
    //     try {
    //         $uuid = $request->uuid;

    //        $user = User::where('uuid', $uuid)->first();

    //         if (!$user) {
    //             return response(['state' => 'error', 'msg' => 'User not found'], 404);
    //         }

    //         $userhasroles = DB::table('user_has_roles')->where('user_id', $user->id)->get();
    //         $assignedSubjects = $user->assignedSubjects();
    //         $assignedClass = $user->assignedClasses();

    //         if (!count($assignedClass) && !count($assignedSubjects)) {
                
    //         if (count($userhasroles)) {
                
    //             $count = $userhasroles->count();
               
    //             foreach ($userhasroles as $key => $role) {
                    
    //                 DB::table('user_has_roles')->where('user_id',$role->user_id)->delete();

    //                 if ($key == ($count-1) ) {
    //                     DB::table('model_has_roles')->where('model_id', $user->id)->delete();
    //                 }

    //             }
    //         }

    //         }
    //         // return 'yeaah';
           
    //         $destroy = User::find($user->id)->delete();
    
    //         if ($destroy) {
    //             return response(['state' => 'done', 'msg' => 'success']);
    //         }
    //     } catch (QueryException $e) {
    //         return response(['state' => 'error', 'msg' => $e->getMessage()], 500);
    //     }
    // }


    public function edit(Request $req){
        $user  = User::where('uuid',$req->uuid)->first();
        return response($user);
      }

      public function registration($uuid=null){

        $data['user'] = null;

        if ($uuid) {

            $data['user'] = User::with('userHasRoles.roles')->where('uuid',$uuid)->first();

        }


        $data['roles'] = Role::where('show_role', 1)->get();

        $data['permissions'] = Permission::all();
        return view('user-management.registration')->with($data);

      }




    public function profile($uuid){

       $data['user'] = $user = User::where('uuid',$uuid)->first();
       $data['uuid'] = $uuid;
       $data['roles'] = $roles = $user->userHasRoles;
       $data['show_subjects_allocation'] = false;

      foreach ($roles as $item) {
        if ($item['role_id'] == 5) {
            $data['show_subjects_allocation'] =  true;
        }
        break; 
        }

       $data['imageUrl'] = '';
       $data['activeTab'] = 'personalInfoTab';

       return view('user-management.profile')->with($data);

        //  return  $user->userHasRoles;

        //     return $uuid;

    }

    public function myProfile($uuid, User $myuser)
    {
        $user = $myuser->with('userHasRoles')->where('uuid', $uuid)->firstOrFail();

        $roles = $user->userHasRoles;
        $showSubjectsAllocation = false;

        foreach ($roles as $item) {
            if ($item->role_id == 5) {
                $showSubjectsAllocation = true;
                break;
            }
        }

        $data = [
            'user' => $user,
            'uuid' => $uuid,
            'roles' => $roles,
            'show_subjects_allocation' => $showSubjectsAllocation,
            'imageUrl' => $user->profile_pic ? asset('storage/' . $user->profile_pic) : '',
            'activeTab' => 'personalInfoTab',
        ];

        return view('user-management.profile', $data);
    }


      public function loginHistory($uuid){

        $data['user'] = $user = User::where('uuid',$uuid)->first();
        $data['roles'] = $user->userHasRoles;
        $data['imageUrl'] = '';
        $data['activeTab'] = 'loginHistoryTab';
        return view('user-management.login_history')->with($data);



      }


      /* ATTACHMENTS */

      public function attachmentsIndex($uuid){

        $data['user'] = $user = User::where('uuid',$uuid)->first();
        $data['roles'] = $user->userHasRoles;
        $data['imageUrl'] = '';
        $data['activeTab'] = 'attachmentsInfoTab';
        return view('user-management.attachments')->with($data);

      }



      /* END */


      /* CONTACT PEOPLE */



      public function contactPeopleIndex($uuid){

        $data['user'] = $user = User::where('uuid',$uuid)->first();
        $data['roles'] = $user->userHasRoles;
        $data['imageUrl'] = '';
        $data['activeTab'] = 'contactPersonTab';
        return view('user-management.contact_person')->with($data);

      }


      /* END */

      /* SUBJECTS ALLOCATED */


      public function subjectsAllocatedIndex($uuid){
                $data['user'] = $user = User::where('uuid',$uuid)->first();
            $data['roles'] = $user->userHasRoles;
            $data['imageUrl'] = '';
                $data['activeTab'] = 'allocatedSbjctsTab';
                return view('user-management.subjects_allocated')->with($data);

        }


      /* END */

      function editUser($uuid){

            //  return $uuid;
            $data['user'] = null;
            $data['uuid'] = $uuid;

            if ($uuid) {

                $data['user'] = User::with('userHasRoles.roles')->where('uuid',$uuid)->first();

            }
            $data['roles'] = Role::where('show_role', 1)->get();
            $data['permissions'] = Permission::all();
            return response($data);



      }



    public function updateUserBasic(Request $req)
    {
        $user = User::where('uuid', $req->uuid)->first();

        if (!$user) {
            $data = ['title' => 'error', 'msg' => 'There was an error', 'state' => 'error'];
            return response($data);
        }

        $user->update([
            'firstname' => $req->firstname,
            'lastname' => $req->lastname,
            'phone' => $req->phone,
            'username' => $req->username,
            'address' => $req->address,
            'password' => $req->password, 
            'created_by' => auth()->user()->id,
        ]);

    
        if ($user->wasChanged()) {
            $data = ['title' => 'success', 'msg' => 'Info updated', 'state' => 'done', 'user' => $user];
            return response($data);
        }

        $data = ['title' => 'fail', 'msg' => 'Info not updated', 'state' => 'fail'];
        return response($data);
    }


 public function successfulAction($email,$username)
{
    // Your logic for the successful action

    // Send the email
    $userEmail = 'abdultarickh@gmail.com'; // Replace with the user's email
    Mail::to($userEmail)->send(new SignInMail($username));

}


public function deleteStudent($id){
    try {

      $student =   Student::find($id);

      if($student){
          Contact::where('contactable_id',$student->id)->where('contactable_type',Student::class)->delete();
          ContactPerson::where('personable_id',$student->id)->delete();
          $student->delete();

         $data = ['state'=>'Done', 'title'=>'Successful', 'msg'=>'Record deleted successful'];

         return response($data);

      }

      $data = ['state'=>'Fail', 'title'=>'Fail', 'msg'=>'Record could not be deleted'];
        return  response()->json($data);

    } catch (QueryException $e) {

      $data = ['state'=>'Error', 'title'=>'Database error', 'msg'=>'Something went wrong!<br />' . $e->errorInfo[2]];
      return  response()->json($data);
    }

}



public function deleteAttachments($id){

  try {


      $attachment = Attachment::where('attachable_type',Student::class)
      ->where('attachable_id',$id)->first();

      Storage::delete($attachment->path); /* DELETE INTERNAL STORAGE ON HOLD */
      if($attachment){
      $delete = $attachment->delete();

      if($delete){

          $data = ['state'=>'Done', 'title'=>'Successful', 'msg'=>'Record deleted successful'];

         return response($data);

      }
      $data = ['state'=>'Fail', 'title'=>'Fail', 'msg'=>'Record could not be deleted'];
        return  response($data);

          }

  } catch (QueryException $e) {
      $data = ['state'=>'Error', 'title'=>'Database error', 'msg'=>'Something went wrong!<br />' . $e->errorInfo[2]];
      return  response($data);
  }


}



}
