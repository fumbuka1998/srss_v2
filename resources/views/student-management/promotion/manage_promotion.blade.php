@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mt-3 mb-3" style="background-color: white;">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5 pl-3">
            <h1 class="pd-0 mg-0 tx-20 text-overflow">Manage Promotion</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto pr-4">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <span class="breadcrumb-item active">Manage Promotion</span>
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


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="datatable-btns">
                    <span class="float-right">
                        <a href="javascript:void(0)" title="excel" onclick="generateFile('excel')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                        <a href="javascript:void(0)" title="pdf" onclick="generateFile('pdf')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>&nbsp;Pdf</a>
                        {{-- <a href="{{ route('students.registration.single') }}" title="new student" id="register" type="button" class=" btn btn-primary btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;New Student</a> --}}
                    </span>
                </div>
            </div>
            <div class="col-md-12">
                <table id="table" class="table compact table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                    <thead>
                        <tr>
                            @foreach ($headers as $key => $header)
                            <th data-field="{{ $header['name'] }}">{{ $header['label'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                </table>

            </div>
        </div>

    </div>
</div>
    </div>
    </div>

    @section('scripts')
    <script>

        $('#table').DataTable({

        responsive:true,
        scrollX: true,

    language: {
        searchPlaceholder: 'Search...',
        sSearch: ''
    },

        processing: true,
        serverSide: true,
        ajax:'{{ route('manage.promotion.datatable') }}',
        columns:[
        {data: 'name', name:'name'},
        {data: 'gender', name:'gender'},
        {data: 'from_class', name:'from_class'},
        {data: 'from_stream', name:'from_stream'},
        {data: 'to_class', name:'to_class'},
        {data: 'to_stream', name: 'to_stream'},
        {data: 'promotion_date', name: 'promotion_date'},
        {data:'action', name:'action', orderable:false, searchable:false}
        ],
        "columnDefs": [
        // { className: " text-right font-weight-bold", "targets": [ 1 ] },
        // {data: 'avatar', name:'avatar'},
        // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
        // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
      ],

     });

//reset student promotion
$(document).on('click', '.reset-promotion', function() {
    let promotionId = $(this).data('student-id');
    let init_url = '{{ route('reset.student.promotion', [':id']) }}';
    let url = init_url.replace(':id', promotionId);

Swal.fire({
title: "Are you sure?",
text: "You are about to reverse promotion!",
icon: "warning",
showCancelButton: true,
confirmButtonColor: "#3085d6",
cancelButtonColor: "#d33",
confirmButtonText: "Yes, Reverse!"
}).then((result) => {
if (result.isConfirmed) {

    $.ajax({
        type: 'PUT',
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
                    Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    confirmButtonText: 'OK'
                });
                    $('#table').DataTable().ajax.reload();
                },
        error: function(xhr, status, error) {
                    Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred: ' + error,
                    confirmButtonText: 'OK'
                });
            }
    });
}


});

});





    </script>
@endsection
@endsection







