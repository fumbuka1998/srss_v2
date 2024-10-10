
@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mt-3 mb-3 " style="background-color: white;">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5 pl-3">
            <h1 class="pd-0 mg-0 tx-20 text-overflow">STUDENTS PROMOTIONS</h1>
            &nbsp; <small> From Year  <span class="text-danger">  {{ date('Y') }}  </span> -  <span class="text-success"> {{ date('Y')+1 }} </span>  </small>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto pr-4">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <span class="breadcrumb-item active">Students Promotions</span>
        </div>
    </div>
</div>
@endsection

@section('body')

<style>

table th{
background: #069613;
color: #ffffff;
}

</style>


<div class="row mt-4">
    <div class="col-md-6 mt-4">
    <div class="card">
    <div class="card-body">
                <p class="text-center" style="color: #238fcf"> <i class="fa-brands fa-apple"></i> CURRENT CLASS & STREAM</p>

                <form id="classForm">
                    <div class="form-group">
                        <label for="class">From Class</label>
                        <select class="form-control select2s" id="from_class" name="class" required>
                            <option value=""></option>
                            @foreach ($from_classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stream">From Stream</label>
                        <select class="form-control select2s" id="from_stream" name="stream" >
                            <option value="">Select Stream</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info" style="float: right"><i class="fa-solid fa-spinner"></i> Load Students</button>
                </form>


            </div>

        </div>
    </div>

    <div class="col-md-6 mt-4 promotion-class d-none">
        <div class="card">
            <div class="card-body">
                <p class="text-center" style="color: #36a000"><i class="fa-brands fa-apple"></i> PROMOTION CLASS & STREAM</p>

                <form id="promotionForm">
                    <div class="form-group">
                        <label for="to_class">To Class</label>
                        <select class="form-control select2s" id="to_class" name="to_class">
                            <option value=""></option>
                                @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="to_stream">To Stream</label>
                        <select class="form-control select2s" id="to_stream" name="to_stream">
                            <option value=""></option>
                        </select>
                    </div>
                    <button style="float: right" type="button" class="btn btn-success" id="applyToAll"><i class="fa-solid fa-arrow-down-wide-short"></i> Apply To All</button>
                    {{-- <button type="button" class="btn btn-success" id="promoteButton">Promote</button> --}}
                </form>

                    </div>
                </div>

    </div>


</div>

    <div class="row mt-4">
        <br>
        <br>
        <div class="col-md-12" id="studentsTableDiv" style="display:none;">
            <div class="row">
                <div class="col-md-12 mt-2 mb-2">
                    <div class="float-right">
                        <a href="javascript:void(0)" title="Promote Student" id="promote_students" type="button" class=" btn btn-info" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa-solid  fa-arrow-trend-up"></i>&nbsp;Promote Students</a>
                    </div>
                </div>
            </div>
            <div class="datatable-dashv1-list custom-datatable-overright">
                <table id="studentsTable" class="table compact table-bordered table-sm" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="min-width: 5em">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkAll">
                                    <label class="custom-control-label" for="checkAll">CheckAll</label>
                                </div>
                            <th style="min-width: 3em">SN</th>
                            <th style="min-width: 20em">Student Name</th>
                            <th style="min-width: 5em">Gender</th>
                            <th style="min-width: 8em">Current Class</th>
                            <th style="min-width: 8em"> Current Stream </th>
                            <th>Promotion Class</th>
                            <th>Promotion Stream</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>



@endsection

@section('scripts')
<script>

$('#promote_students').hide();


$('#from_class').change(function(){

let class_id = $(this).val();

spark();

  $.ajax({

      url:'{{ route('students.from_class.filter') }}',
      data:{
          class_id : class_id
      },

      success: function(response){

          $('#from_stream').html(response);
          setTimeout(function(){
            unspark();
          },1000)

      },
      error:function(){
        unspark();
      }
  });
});


$('#to_class').change(function(){

let class_id = $(this).val();
spark();
  $.ajax({
      url:'{{ route('students.to_class.filter') }}',
      data:{
          class_id : class_id
      },
      success: function(response){

           $('#to_stream').html(response);
           unspark();

      },
      error:function(res){
        unspark()
      }
  });
});


    $(document).ready(function() {
        let dataTable = null;
        let selectedStudentIds = [];

                //function to initialize the datatable
                function initializeDataTable(classId,streamId){

dataTable = $('#studentsTable').DataTable({
    responsive:true,
        scrollX: true,

    language: {
        searchPlaceholder: 'Search...',
        sSearch: ''
    },
    columns: [
        {data: 'checkbox', name:'checkbox',orderable:false, searchable:false},
        {data: 'sn', name:'sn'},
        {data: 'avatar', name:'avatar'},
        { data: 'gender', name:'gender'},
        { data: 'current_class', name: 'current_class' },
        { data: 'current_stream', name: 'current_stream' },
        { data: 'promotion_class', name: 'promotion_class' },
        { data: 'promotion_stream', name: 'promotion_stream' },
    ],
    drawCallback(){

        let checkboxes = $(".promote-checkbox");

        $('#applyToAll').click(function(){

        let class_id = $('#to_class').val();
        let stream_id = $('#to_stream').val();

        let elementClass =  $('body').find('.select_dt_promotion_class');

        elementClass.each(function() {

        $(this).val(class_id).trigger('change');

        });

        let elementStream = $('body').find('.select_dt_promotion_stream');

        spark();

        $.ajax({
        url:'{{ route('students.to_class.filter') }}',
        data:{
        class_id : class_id
        },
        success: function(res){

        elementStream.each(function() {
            let elem = $(this);
            elem.html(res)
            elem.val(stream_id).trigger('change');
        });
        unspark();
        },
        error:function(res){
        unspark()
        }
        });


        });

        /* single checkbox change */

        $('.select_dt_promotion_class').change(function(){
        let class_id = $(this).val()
        let stream_element = $(this).closest('tr').find('.select_dt_promotion_stream');

        spark();
        $.ajax({
        url:'{{ route('students.to_class.filter') }}',
        data:{
        class_id : class_id
        },
        success: function(res){
        stream_element.html(res)
        unspark();
        },
        error:function(res){
        unspark()
        }
        });

        })


        /* end checkbox change */

            $('#checkAll').click(function(){

            if ($(this).prop('checked')) {
            checkboxes.prop('checked', true);
            updatePromoteButtonVisibility();
            selectedStudentIds = checkboxes.map(function() {

            let el = $(this);
            let row = el.closest('tr');
            let studentId = el.data('student-id');
            let toClass = row.find('.select_dt_promotion_class').val();
            let toStream = row.find('.select_dt_promotion_stream').val();
            return { 'student_id': studentId, 'to_class': toClass, 'to_stream': toStream };

            }).get();

            } else {

            checkboxes.prop('checked', false);
            updatePromoteButtonVisibility();
            selectedStudentIds = [];

            }
            });

/* HERE FROM GPT */

let checkAllCheckbox = $('.checkAll');
let promoteButton = $('#promote_students');

checkboxes.click(function() {

$(this).prop('checked', $(this).prop('checked'));

updateCheckAllState();
updatePromoteButtonVisibility();

console.log($(this))

selectedStudentIds = checkboxes.filter(':checked').map(function() {
let row = $(this).closest('tr');
let studentId = $(this).data('student-id');
let toClass = row.find('.select_dt_promotion_class').val();
let toStream = row.find('.select_dt_promotion_stream').val();
return { 'student_id': studentId, 'to_class': toClass, 'to_stream': toStream };
}).get();

});



checkAllCheckbox.click(function() {
checkboxes.prop('checked', $(this).prop('checked'));

if ($(this).prop('checked')) {

            checkboxes.prop('checked', true);
            updatePromoteButtonVisibility();
            selectedStudentIds = checkboxes.map(function() {

            let el = $(this);
            let row = el.closest('tr');
            let studentId = el.data('student-id');
            let toClass = row.find('.select_dt_promotion_class').val();
            let toStream = row.find('.select_dt_promotion_stream').val();
            return { 'student_id': studentId, 'to_class': toClass, 'to_stream': toStream };

            }).get();


            }
            else {

            checkboxes.prop('checked', false);
            updatePromoteButtonVisibility();
            selectedStudentIds = [];

            }



selectedStudentIds = checkboxes.map(function() {
return $(this).data('student-id');
})
updatePromoteButtonVisibility();
});

function updateCheckAllState() {
let totalCheckboxes = checkboxes.length;
let checkedCheckboxes = checkboxes.filter(':checked').length;

if (checkedCheckboxes === 0) {
checkAllCheckbox.prop('indeterminate', false);
checkAllCheckbox.prop('checked', false);
} else if (checkedCheckboxes === totalCheckboxes) {
checkAllCheckbox.prop('indeterminate', false);
checkAllCheckbox.prop('checked', true);
} else {
checkAllCheckbox.prop('indeterminate', true);
}
}

function updatePromoteButtonVisibility() {
if ($('.promote-checkbox:checked').length === 0) {
promoteButton.hide('slow');
} else {
promoteButton.show('slow');
}
}

/* END */
    },
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('students.promotion.datatable') }}',
        type: 'POST',
        data: { classId: classId,streamId:streamId, _token: '{{ csrf_token() }}' },
    },
    initComplete: function () {
    unspark();



    },
});

}

        $('#classForm').submit(function(event) {
            event.preventDefault();
            spark();

            let classId = $('#from_class').val();
            let streamId = $('#from_stream').val();

            if (dataTable !== null) {
                dataTable.destroy();
            }

            initializeDataTable(classId,streamId);

           $('.promotion-class').removeClass('d-none');
            $('#studentsTableDiv').show();
            $('#promoteButtonDiv').show();
        });



$("#promote_students").click(function(){

Swal.fire({
title: "Are you sure?",
text: "You are about to make promotion!",
icon: "warning",
showCancelButton: true,
confirmButtonColor: "#3085d6",
cancelButtonColor: "#d33",
confirmButtonText: "Yes, Promote!"
}).then((result) => {
if (result.isConfirmed) {
  spark();
  let fromClass = $('#from_class').val();
  let fromStream = $('#from_stream').val();

  $.ajax({
  url:'{{ route('students.promote') }}',
  type:'POST',
  data:{
  studentsContent : selectedStudentIds, fromClass:fromClass, fromStream:fromStream,  _token: '{{ csrf_token() }}'
  },
  success: function(res){

    if (dataTable !== null) {
                dataTable.destroy();
            }

    initializeDataTable(res.class_id,res.stream_id);

    if (res.state == 'done') {
    Swal.fire({
    title: "promoted!",
    text: `${res.msg}`,
    icon: `${res.title}`
    });

    }
    if(res.state == 'fail'){

    Swal.fire({
    title: "Not Promoted!",
    text: `${res.msg}`,
    icon: `${res.title}`,
    footer: `Possible error: <i class="fa-solid text-danger fa-triangle-exclamation"></i>  Class or Stream Not Selected`
    });

    }

    unspark();

  },
  error:function(res){
    console.log(res)

Swal.fire({
  icon: "error",
  title: "Oops...",
  text: `${res.msg}`,
});

  unspark()

  }

  });






}
});





})


    });



</script>
@endsection
