<?php

namespace App\Http\Controllers\configurations\clubs;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClubsController extends Controller
{



    public function index(){

        $data['clubs'] = Club::all();
        return view('configurations.clubs.index')->with($data);

        }


        public function datatable(Request $request){


            try {


           $clubs = Club::all();

              $search = $request->get('search');

            if(!empty($search)){

         //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
         //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
         //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
         //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
         //    }

                 // $invoices = $invoices->groupBy('invoices.id');
            }

             return DataTables::of($clubs)

             ->addColumn('name',function($club){

                 return $club->name;

             })

             ->editColumn('created_by',function($club){
                $user = User::find($club->created_by);
                return $user ? $user->full_name : 'admin';
             })

             ->addColumn('action',function($club){
                 return '<span>
                 <button type="button" data-uuid="'.$club->uuid.'" class="btn btn-custon-four btn-info btn-sm edit"><i class="fa fa-edit"></i></button>
                 | <button data-uuid="'.$club->uuid.'" type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
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

                $club = Club::updateOrCreate(
                    [

                        'uuid' =>$req->uuid

                    ],

                    [

                    'name'=> strtoupper($req->club),
                    'uuid'=>generateUuid(),
                    'created_by'=>auth()->user()->id

                   ]);

                   if ($club) {

                    return response(['state'=>'done', 'msg'=> 'success','title'=>'success']);

                   }


            } catch (QueryException $e) {

                return $e->getMessage();

            }
        }


        public function destroy(Request $req){

            try {
            $uuid = $req->uuid;
            $club = Club::where('uuid',$uuid)->first();
            $destroy = $club->delete();

            if ($destroy) {

                return response(['state'=>'done', 'msg'=>'success', 'title'=>'success']);
                # code...
            }

            } catch (QueryException $e) {

                return $e->getMessage();
            }

        }


        public function edit(Request $req){


            $club  = Club::where('uuid',$req->uuid)->first();
            return response($club);



          }








}
