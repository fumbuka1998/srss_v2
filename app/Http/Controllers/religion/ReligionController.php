<?php

namespace App\Http\Controllers\Configurations\religion;

use App\Http\Controllers\Controller;
use App\Models\Religion;
use App\Models\ReligionSect;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReligionController extends Controller
{


public function index(){


$data['religions'] = Religion::all();
return view('configurations.religion.index')->with($data);


}






public function datatable(Request $request){


    try {


   $religions = Religion::all();

      $search = $request->get('search');

    if(!empty($search)){

 //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
 //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
 //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
 //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
 //    }

         // $invoices = $invoices->groupBy('invoices.id');
    }

     return DataTables::of($religions)

     ->addColumn('name',function($religion){

         return $religion->name;

     })

     ->editColumn('sect', function($religion){
        $sects = $religion->sects;
        $the_sects = '';
        foreach ($sects as $key => $sect) {

            $the_sects .= '<button style="padding: 2px 5px;border-radius: 5px;  color: darkblue; border: 1px dotted rgba(0, 0, 139, 0.596);"> '.$sect->name.' </button> &nbsp;';

        }

       return $the_sects;

     })

     ->editColumn('created_by',function($religion){

        $user = User::find($religion->created_by);
        return $user ? $user->full_name : 'admin';
     })

     ->addColumn('action',function($religion){
         return '<span>
         <button type="button" data-uuid="'.$religion->uuid.'" class="btn btn-custon-four btn-info btn-sm edit"><i class="fa fa-edit"></i></button>
         | <button data-uuid="'.$religion->uuid.'" type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
     </span>';

     })

   ->rawColumns(['action','sect'])
   ->make();

    } catch (QueryException $e) {

       return $e->getMessage();

    }

 }


 public function store(Request $req){

    try {



        $religion = Religion::updateOrCreate(
            [

                'uuid' =>$req->uuid

            ],

            [

            'name'=>$req->name,
            'uuid'=>generateUuid(),
            'created_by'=>auth()->user()->id

           ]);

           if ($religion) {

            return response(['state'=>'done', 'msg'=> 'success', 'title'=>'success']);


           }


    } catch (QueryException $e) {

        return $e->getMessage();

    }
}


public function destroy(Request $req){

    try {
    $uuid = $req->uuid;
    $religion = Religion::where('uuid',$uuid)->first();
    $destroy = $religion->delete();

    if ($destroy) {

        return response(['state'=>'done', 'msg'=>'success', 'title'=>'success']);
        # code...
    }

    } catch (QueryException $e) {

        return $e->getMessage();
    }

}


public function edit(Request $req){


    $religion  = Religion::where('uuid',$req->uuid)->first();
    return response($religion);



  }




public function sectDatatable(Request $request){

    try {


        $religions_sects = ReligionSect::all();

           $search = $request->get('search');

         if(!empty($search)){

      //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
      //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
      //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
      //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
      //    }

              // $invoices = $invoices->groupBy('invoices.id');
         }

          return DataTables::of($religions_sects)

          ->addColumn('name',function($sect){

              return $sect->name;

          })

          ->editColumn('created_by',function($sect){

            $user = User::find($sect->created_by);
            return $user ? $user->full_name : 'admin';
          })

          ->addColumn('action',function($sect){
              return '<span>
              <button type="button" data-uuid="'.$sect->uuid.'" class="btn edit-sect btn-custon-four btn-info btn-sm"><i class="fa fa-edit"></i></button>
              | <button data-uuid="'.$sect->uuid.'" type="button" class="btn btn-custon-four btn-danger btn-sm delete-sect"><i class="fa fa-trash"></i></button>
          </span>';

          })

        ->rawColumns(['action'])
        ->make();

         } catch (QueryException $e) {

            return $e->getMessage();

         }





}


public function sectStore(Request $req){

    try {

        $rs = ReligionSect::updateOrCreate(
            [

                'uuid' =>$req->uuid

            ],

            [

            'name'=>$req->name,
            'religion_id'=>$req->religion_id,
            'uuid'=>generateUuid(),
            'created_by'=>1

           ]);

           if ($rs) {

            return response(['state'=>'done', 'msg'=> 'success','title'=>'success']);


           }


    } catch (QueryException $e) {

        return $e->getMessage();

    }

}


public function sectEdit(Request $req){

    $religionSect  = ReligionSect::where('uuid',$req->uuid)->first();
    return response($religionSect);


}


public function sectDestroy(Request $req){

    try {
        $uuid = $req->uuid;
        $religion_sect = ReligionSect::where('uuid',$uuid)->first();
        $destroy = $religion_sect->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success', 'title'=>'success']);

        }

        } catch (QueryException $e) {

            return $e->getMessage();

        }





}


public function getSects(Request $req){

    try {

            $religion_id = $req->religion_id;

            $sects = Religion::find($religion_id)->sects;

            $sectHtml = '<option> Filter By Sect </option>';

            foreach ($sects as $key => $sect) {

                $sectHtml .= '<option value="'.$sect->id.'"> '.$sect->name.' </option>';

            }

            return response($sectHtml);


        } catch (QueryException $e) {

            return $e->getMessage();

    }


}



}
