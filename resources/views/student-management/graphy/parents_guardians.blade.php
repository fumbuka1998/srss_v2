@extends('layout.index')
@section('top-bread')
@include('student-management.profile_breadcrumb')
@endsection
@section('body')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css ">
<style>

.image-container {
    /* position: relative; */
    width: 200px; /* Adjust the size as needed */
} 

.edit-icon {
    position: absolute;
    font-size: 22px;
    color: #007bff;
    cursor: pointer;
}

.user-profile-img img {
border-radius: 0 !important;

}
 .th-hover:hover{
    cursor: pointer;
}

.col-border{
    border-right: 1px solid #17a2b8;
    top: 0;
}

.col-border-top{
    border-top: 1px solid #17a2b8;
}

</style>

<div class="card">

    <div class="card-body">


        <div class="row">


          @include('student-management.profile_part')

            <div class="col-md-9">
                @include('student-management.the_nav')

                <style>

                .data-head {
                    padding: 0.5rem 1.25rem;
                    margin-bottom: 0.25rem;
                    background-color: #ebeef2;
                    border-radius: 4px;
                }

                .col-display{
                    flex-direction: column;
                }
                .th-color{
                   background-color: #069613;
                   color: #ffff;
                }
                .s1{
                    color: #069613;
                }





                </style>

               <div>

        <div class="row">
            <div class="col-md-12 mt-3 mb-1">
                <div class="float-right">
                    <a href="javascript:void(0)" title="new invoice" id="register" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;Add Contact Person</a>
                </div>


            </div>
        </div>
                <div class="row mt-4 col-display">
                    <div class="col-md-12">
                        <table class="table responsive compact" id="contact_people_table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-color">Full Name</th>
                                    <th class="th-color">Relationship</th>
                                    <th class="th-color">Phone #</th>
                                    <th class="th-color">Occupation</th>
                                    <th class="th-color"></th>
                                </tr>
                            </thead>

                        </table>
                    </div>

                </div>
               </div>




            </div>
        </div>
    </div>
</div>


{{-- MODAL FROM PAYFEE --}}

<div class="modal fade" id="contact_person_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel">Add Contact Person</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body">
             <form id="contact_person_form" method="POST">
                @csrf
             <div id="cntP">
             <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="contact_person_relationship"><i class="fa-solid fa-people-roof s1"></i> Next of KiN Relationship:</label>
                        <select name="contact_person_relationship" id="contact_person_relationship" class="form-control relationship select2s form-control-sm">
                           <option value="FATHER">Father</option>
                           <option value="MOTHER">Mother</option>
                           <option value="GUARDIAN">Guardian</option>
                        </select>
                     </div>
                     <input type="hidden" name="contact_person_id" id="contact_person_id">
                     <input type="hidden" name="contact_person_contact_id" id="contact_person_contact_id">


                </div>
                <div class="col-md-12">
                   <div class="form-group">
                       <label for="name"> <i class="fa fa-user s1"></i> Name:</label>
                       <input name="contact_person_name" id="contact_person_name" class="form-control name form-control-sm">
                    </div>
               </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="Occupation"> <i class="fa-solid fa-cable-car s1"></i> Occupation:</label>
                        <input name="contact_person_occupation" id="contact_person_occupation" class="form-control occupation form-control-sm">
                     </div>
                </div>
                <div class="col-md-12">
                   <div class="form-group">
                       <label for="Phone"><i class="fa fa-phone s1"></i> Phone #:</label>
                       <input type="number" name="contact_person_phone" id="contact_person_phone" class="form-control phone form-control-sm">
                    </div>
               </div>
            </div>
          </div>
       </form>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
             <button type="button" id="save_contact_person" class="btn btn-info">Save changes</button>
          </div>
       </div>
    </div>
 </div>

 {{-- // function upload(event){

    // let profile_url = '{{ route('students.profile.pic.update',':id') }}';
    // let final_profile_url = profile_url.replace('id', uuid);

    //    data = new FormData($('#profile_submit')[0]);

    // $.ajax({
    //       type:'POST',
    //       processData: false,
    //       contentType: false,
    //       enctype: 'multipart/form-data',
    //       url:final_profile_url,
    //       data:data,
    //       success:function(response){
    //          console.log(response)

    //          $("#profile_image_update").attr("src",response);
    //       }

    // });

    // } --}}

{{-- END MODAL FROM PAYFEE --}}



@section('scripts')
<script>

let uuid = @json($uuid);

$('#save_contact_person').click(function(){
let init_url = '{{ route('student.profile.contact.people.store',':id')  }}';
let url = init_url.replace(':id',uuid);
let form_data = new FormData($('#contact_person_form')[0])

    $.ajax({
                processData: false,
                contentType: false,
                method:'POST',
                url:url,
                data:form_data,
                beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },

                success:function(res){
                    unspark()
                    if(res.title == 'success'){

                        $('#contact_person_modal').modal('hide');

                    }
                    toast(res.msg,res.title)
                    datatable.draw()
                },
                error:function(res){
                    unspark()
                    toast(res.msg,res.title)
                }

                });



})


/* ADD  MODAL*/
$('#register').click(function(){
    let form = $('#contact_person_form')[0];
    clearForm(form)
$('#contact_person_modal').modal('show');

})




/* UPDATE PROFILE PIC */



/* END */


/* datatable */
let contact_people_url_init = '{{ route('students.profile.contact.people.datatable',[':id']) }}';
let cnct_url = contact_people_url_init.replace(':id', uuid);
let datatable = $('#contact_people_table').DataTable({
        processing: true,
        serverSide: true,
        ajax:cnct_url,
        columns:[
      {data: 'full_name', name:'full_name'},
      {data: 'relationship', name:'relationship'},
      {data: 'phone', name:'phone'},
      {data: 'occupation', name:'occupation'},
      {data:'action', name:'action', orderable:false, searchable:false}
        ],
        "columnDefs": [
        // { className: " text-right font-weight-bold", "targets": [ 1 ] },
        // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
        // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
      ],

      drawCallback:function(){

$('.delete').click(function(){
    let url = "{{ route("academic.class.teachers.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});

$('.editCntBtn').click(function(){
    spark();

    let id = $(this).data('contact_person_id');

    let init_url = '{{ route('student.profile.contact.people.edit',[':uuid',':id']) }}'
    let url = init_url.replace(':uuid',uuid).replace(':id',id)
    $.ajax(

    {
        url:url,
        method:"POST",
    beforeSend: function(xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },
    success:function(res){
        $('#contact_person_relationship').val(res.contact_person.relationship).trigger('change');
        $("#contact_person_name").val(res.contact_person.full_name);
        $('#contact_person_occupation').val(res.contact_person.occupation);
        $('#contact_person_phone').val(res.contact_person.contacts[0].contact);
        $('#contact_person_contact_id').val(res.contact_person.contacts[0].id);
        $('#contact_person_id').val(res.contact_person.id);

    $('#contact_person_modal').modal('show');
    unspark()

    },
    error:function(res){

        unspark()

    }

    })




})

}

        });







</script>

@endsection



@endsection
