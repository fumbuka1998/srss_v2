<?php

namespace App\Http\Controllers\configurations\houses;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HousesController extends Controller
{



    public function index(){

        $data['houses'] = House::all();
        return view('configurations.houses.index')->with($data);

        }



        public function datatable(Request $request){


            try {


           $houses = House::all();

              $search = $request->get('search');

            if(!empty($search)){

         //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
         //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
         //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
         //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
         //    }

                 // $invoices = $invoices->groupBy('invoices.id');
            }

             return DataTables::of($houses)

             ->addColumn('name',function($house){

                 return $house->name;

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



                $house = House::updateOrCreate(
                    [

                        'uuid' =>$req->uuid

                    ],

                    [

                    'name'=>$req->house,
                    'uuid'=>generateUuid(),
                    'created_by'=>auth()->user()->id

                   ]);

                   if ($house) {

                    return response(['state'=>'done', 'msg'=> 'success','title'=>'success']);


                   }


            } catch (QueryException $e) {

                return $e->getMessage();

            }
        }


        public function destroy(Request $req){

            try {
            $uuid = $req->uuid;
            $house = House::where('uuid',$uuid)->first();
            $destroy = $house->delete();

            if ($destroy) {

                return response(['state'=>'done', 'msg'=>'success', 'title'=>'success']);
                # code...
            }

            } catch (QueryException $e) {

                return $e->getMessage();
            }

        }


        public function edit(Request $req){


            $house  = House::where('uuid',$req->uuid)->first();
            return response($house);



          }




}
