@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mt-3 mb-3" style="background-color: white;">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5 pl-3">
            <h1 class="pd-0 mg-0 tx-20 text-overflow new-header">CLASSES SUBJECTS ALLOCATION</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto pr-4">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a>
            <span class="breadcrumb-item active">Classes Subjects Allocation</span>
        </div>
    </div>
</div>
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

.cancelled{
    text-decoration: line-through;
}

.user-profile-img img {
border-radius: 0 !important;

}

.custom-control-input{

    cursor: pointer;
    width: 1.5em;
    height: 1.5em;



}



.custom-control-label {
            padding-left: 5px;
        }

        .custom-control-input:checked + .custom-control-label::before {
            background-color: #069613 !important;
            border-color: #069613 !important;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-label {
            margin-bottom: 0;
            flex-grow: 1;
        }

        .checkbox-number {
            margin-right: 10px;
            font-weight: bold;
        }

        #table th{
        color: white !important;
        background:#069613;
    }

    .animated-checkbox{
        cursor: pointer;
        width: 1.3em;
        height: 1.3em;
    }

.table-wrapper .frozen{
position: sticky;
left: 0;
z-index: 2;
/* background-color: #fff; */

}

.table-wrapper .frozen-td{
background-color: #fff;
}

</style>

<div class="card">

    <div class="card-body">
        <form id="acmdform">
        <div class="row">
            <div  class="col-md-12">

                <div class="table-container-scroll">
                    <div class="table-wrapper">
                        <table id="table"  class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit;">
                            <thead>
                                <tr>
                                  <th class="" style="min-width: 10rem">Class</th>
                                  <th class="">Stream</th>
                                  <th>Subjects</th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- @foreach ($class_streams as  $cst)

                                <tr>
                                    <td>{{ $cst->class_name  }}</td>
                                    <td>{{ $cst->stream_name }}</td>
                                    <td>
                                        <div style="display: flex; align-items:center"> --}}


                                            {{-- @foreach ($subjects as $index => $subject )
                                            @php
                                                $checked = false;
                                                if ($assignedSubjects){
                                                    foreach ($assignedSubjects as $key => $assignee) {
                                                        if ($cst->class_id == $assignee->class_id && $assignee->stream_id == $cst->stream_id && $assignee->subject_id == $subject->id) {
                                                           $checked = true;
                                                           break;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            <input  type="checkbox" style="margin-left: 1.5rem;"
                                                    data-subject-id="{{ $subject->id  }}"
                                                    value="{{ $subject->id  }}"
                                                    name="subject"
                                                    data-class-id="{{ $cst->class_id }}"
                                                    data-stream-id = "{{ $cst->stream_id }}"
                                                    class="animated-checkbox checkuncheck"
                                                    {{ $checked ? 'checked' : ''  }}>
                                            <span style="margin-left: 0.5rem;" class="text-color font">{{ $subject->name }} </span>

                                            @endforeach --}}

                                        

                                            @foreach ($class_streams as $cst)
                                            <tr>
                                                <td>{{ $cst->class_name }}</td>
                                                <td>{{ $cst->stream_name }}</td>
                                                <td>
                                                    <div style="display: flex; align-items:center">
                                                        @php
                                                            $filteredSubjects = $subjects
                                                                ->where('education_level_name', $cst->education_level_name)
                                                                ->unique('id');
                                                        @endphp
                        
                                                        @foreach ($filteredSubjects as $subject)
                                                            @php
                                                                $checked = $assignedSubjects[$cst->class_id][$cst->stream_id][$subject->id] ?? false;
                                                            @endphp
                        
                                                            <input type="checkbox" style="margin-left: 1.5rem;"
                                                                data-subject-id="{{ $subject->id }}"
                                                                value="{{ $subject->id }}"
                                                                name="subject"
                                                                data-class-id="{{ $cst->class_id }}"
                                                                data-stream-id="{{ $cst->stream_id }}"
                                                                class="animated-checkbox checkuncheck"
                                                                {{ $checked ? 'checked' : '' }}>
                                                            <span style="margin-left: 0.5rem;" class="text-color font">{{ $subject->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        </form>


        <div class="row">
            <div class="col-md-12">
                <div class="display_the_list">


                </div>
            </div>


        </div>


    </div>
</div>

@section('scripts')
<script>

manipulateCheckboxes()

function manipulateCheckboxes(){

$(".checkuncheck").change(function(e) {

let subject_id = e.target.value;
let class_id = $(this).data('class-id');
let stream_id = $(this).data('stream-id');
spark()
let grant = parseInt(0);

if($(this).prop('checked')){
grant = parseInt(1);
}

let url = '{{ route('streams.subjects.assignment.general.mono.update')}}';

$.ajax({

url:url,
type:'POST',
data:{
    subject_id : subject_id,
    grant:grant,
    class_id:class_id,
    stream_id:stream_id,
},

beforeSend: function(xhr) {

        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },

success:function(res){
    unspark();
    toast(res.msg,res.title)
    console.log(res)

},

error:function(res){

    console.log(res)
    unspark();

}

})
});


}


</script>


@endsection



@endsection

