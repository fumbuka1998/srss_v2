
@extends('layout.index')


@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">GENERATED REPORTS INDRIVE</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('results.reports.generated.reports.index')}}"><i class="icon ion-ios-home-outline"></i> Generated Reports</a>
            <span class="breadcrumb-item active mr-3">Generated Reports Indrive</span>
        </div>
    </div>
</div>
@endsection

@section('body')

<style>


#downloadIcon {
    font-size: 2rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-20px);
    }
    60% {
        transform: translateY(-10px);
    }
}

/* Make the first three columns sticky */
/* end */
</style>

<div class="card mt-4">
    <div class="card-body">
    <div class="row clearfix">


        <div class="col-md-12">
            @include('results.reports.generated.indrive_nav')
        </div>


        <div class="col-md-12">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="datatable-btns">
                                <span class="float-right">
                                    <a href="javascript:void(0)" title="excel" onclick="generateFile('excel')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                                    <a href="javascript:void(0)" title="pdf" onclick="generateFile('pdf')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>&nbsp;Pdf</a>
                                    @if (auth()->user()->hasRole('Teacher') && $generated_exam_report->escalation_level_id == 1 || auth()->user()->hasRole('Admin'))
                                    <a href="{{ route('character.assessments.reports.create',$uuid)  }}" title="new Club" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;Add Character Assessments For the Respective Report </a>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                                <thead>
                                    <tr>
                                      <th class="color">Admission No</th>
                                      <th class="color" style="min-width: 15rem">Full Name</th>
                                      <th class="color">901</th>
                                      <th class="color">902</th>
                                      <th class="color">903</th>
                                      <th class="color">904</th>
                                      <th class="color">905</th>
                                      <th class="color">906</th>
                                      <th class="color">907</th>
                                      <th class="color">908</th>
                                      <th class="color">909</th>
                                      <th class="color">910</th>
                                      <th class="color">911</th>
                                      <th class="color">Attendance</th>
                                      <th class="color">Late</th>
                                      <th class="color"></th>
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
            </div>
        {{-- </div> --}}

    </div>
    </div>
</div>
</div>
    </div>
 </div>


 {{-- Modal --}}


 <div id="academic_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-color-modal bg-color-1 mh-bg">
                <h4 class="modal-title">Edit Character Assessment</h4>
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
            </div>
            <div class="modal-body">
                <form action="#" id="cform">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  901 </label>
                            <select type="text" name="code_901" id="code_901"  class="form-control select2s code_901" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  902  </label>
                            <select type="text" name="code_902" id="code_902"  class="form-control select2s code_902" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  903  </label>
                            <select type="text" name="code_903" id="code_903"  class="form-control select2s code_903" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  904  </label>
                            <select type="text" name="code_904" id="code_904"  class="form-control select2s code_904" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  905  </label>
                            <select type="text" name="code_905" id="code_905"  class="form-control select2s code_905" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  906  </label>
                            <select type="text" name="code_906" id="code_906"  class="form-control select2s" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  907  </label>
                            <select type="text" name="code_907" id="code_907"  class="form-control select2s" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold"> 908  </label>
                            <select type="text" name="code_908" id="code_908"  class="form-control select2s" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  909  </label>
                            <select type="text" name="code_909" id="code_909"  class="form-control select2s" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  910  </label>
                            <select type="text" name="code_910" id="code_910"  class="form-control select2s" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold">  911  </label>
                            <select type="text" name="code_911" id="code_911"  class="form-control select2s" placeholder="eg A..">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>

            </form>
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn" href="#">Cancel</a>
                <a href="javascript:void(0)" id="sbmt-btn"  class="btn btn-info">Submit</a>
            </div>
        </div>
    </div>
</div>



{{-- end --}}



@section('scripts')

<script>

$(document).ready(function(){
    let uuid = @json($uuid);

$('#mba').click(function(){
let url = '{{ route('character.assessments.excel.template',':id') }}';

window.open(url.replace(':id',uuid),'_blank');
})




// character.assessments.report.datatable
//datatable

let datatable_url = '{{ route('character.assessments.report.datatable',':uuid') }}';

let datatable = $('#table').DataTable({
        processing: true,
        serverSide: true,
        scrollX:true,
        responsive:true,
        ajax:datatable_url.replace(':uuid',uuid),
        columns:[
        {data: 'admission_number', name:'admission_number'},
        {data: 'full_name', name:'full_name'},
        {data: 'code_901', name:'code_901'},
        {data: 'code_902', name:'code_902'},
        {data: 'code_903', name:'code_903'},
        {data: 'code_904', name:'code_904'},
        {data: 'code_905', name:'code_905'},
        {data: 'code_906', name:'code_906'},
        {data: 'code_907', name:'code_907'},
        {data: 'code_908', name:'code_908'},
        {data: 'code_909', name:'code_909'},
        {data: 'code_910', name:'code_910'},
        {data: 'code_911', name:'code_911'},
        {data: 'attendance', name:'attendance'},
        {data: 'late', name:'late'},
        {data:'action', name:'action', orderable:false, searchable:false}
        ],
        "columnDefs": [
        // { className: " text-right font-weight-bold", "targets": [ 1 ] },
        // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
        // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
      ],

      drawCallback:function(){

// $('.delete').click(function(){
//     let uuid  = $(this).data('uuid');
//     let url = "{{ route("clubs.destroy") }}"
//     let method = "DELETE"
//     ajaxQuery({url:url,method:method,uuid:uuid})
// });

$('.edit').click(function(){

    spark()

    let id = $(this).data('id');
    let student_id = $(this).data('student_id');

    let url = '{{ route('character.assessments.reports.edit',':id') }}'

    $.ajax(

    {
        url:url.replace(':id',id),
        data:{student_id:student_id},
        method:"POST",
    beforeSend: function(xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },
    success:function(res){
        // let id = res.
        // $('#')
        let data = res.character_data;
        let consolidated_data = data[res.student_id];
        $('#code_901').val(consolidated_data.code_901).trigger('change')
        $('#code_902').val(consolidated_data.code_902).trigger('change')
        $('#code_903').val(consolidated_data.code_903).trigger('change')
        $('#code_903').val(consolidated_data.code_903).trigger('change')
        $('#code_904').val(consolidated_data.code_904).trigger('change')
        $('#code_905').val(consolidated_data.code_905).trigger('change')
        $('#code_906').val(consolidated_data.code_906).trigger('change')
        $('#code_907').val(consolidated_data.code_907).trigger('change')
        $('#code_908').val(consolidated_data.code_908).trigger('change')
        $('#code_909').val(consolidated_data.code_909).trigger('change')
        $('#code_910').val(consolidated_data.code_910).trigger('change')
        $('#code_911').val(consolidated_data.code_911).trigger('change')

    // $('#uuid').val(res.uuid);
    // $('#club').val(res.name);
    $('#academic_modal').modal('show');

    unspark();

    if (res.state == 'done') {

    datatable.draw();

    }
    },
    error:function(res){

        unspark();

    }

    })

})

}
        });








})







/* END OF AN ATTEMPT */

</script>


@endsection
@endsection



















