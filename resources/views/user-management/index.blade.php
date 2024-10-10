@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mt-3 mb-3" style="background-color: white;">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5 ">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">USER MANAGEMENT</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto ">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            {{-- <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a> --}}
            <span class="breadcrumb-item active mr-3">User Management</span>
        </div>
    </div>
</div>
@endsection

@section('body')

<style>
    .datatable-btns{

        float: right;

    }

table th{

    background: #069613;
    color: white;
}

</style>

<div class="card">
    <div class="card-body">
        {{-- @include('user-management.tabs') --}}
        <div class="row">
            <div class="col-md-12 mt-2 mb-3">
                <div class="float-left">
                    <a  title="Show Filters" id="accordion" class="text-dark collapsed btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa-solid fa-filter"></i>&nbsp;Filters</a>
                </div>

                <div class="float-right">

                    <a href="javascript:void(0)" title="excel" onclick="generateFile('excel')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                    <a href="javascript:void(0)" title="pdf" onclick="generateFile('pdf')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>&nbsp;Pdf</a>
                    <a href="{{ route('users.management.registration') }}" title="Add User" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;Add</a>

                </div>
            </div>
        </div>

        <div id="accordion1" class="collapse mt-2" data-parent="#accordion">
            <div class="card-body">
                <form action="" id="filter_form">

                    <div class="row">

                        <div class="col-md-10">

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="class">Role</label>
                                        <select name="class" id="class" class="form-control select2s">
                                            <option value="">Filter By Role</option>
                                            @foreach ($roles as $role )
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="stream">Stream</label>
                                        <select name="stream" id="stream" class="form-control select2s">
                                            <option value="">Filter By Stream</option>
                                            @foreach ($streams as $stream )
                                            <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="religion">Religion</label>
                                        <select name="religion" id="religion" class="form-control select2s">
                                            <option value="">Filter By Religion</option>
                                            @foreach ($religions as $religion )
                                            <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sect">Religion Sect</label>
                                        <select name="sect" id="sect" class="form-control select2s">
                                            <option value="">Filter By Religion Sect</option>
                                            @foreach ($religion_sects as  $sect)
                                                <option value="{{ $sect->id }}"> {{ $sect->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="club">Club</label>
                                        <select name="club" id="club" class="form-control select2s">
                                            <option value="">Filter By Club</option>
                                            @foreach ($clubs as $club )
                                            <option value="{{ $club->id }}"> {{ $club->name  }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="house">House</label>
                                        <select name="house" id="house" class="form-control select2s">
                                            <option value="">Filter By House</option>
                                            @foreach ($houses as $house)
                                            <option value="{{ $house->id }}">{{ $house->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}


                            </div>

                        </div>

                        <div class="col-md-2" style="display: flex; flex-direction:column; justify-content:space-around; align-items:center;">
                            {{-- <div class="form-group"> --}}
                            <a  title="Clear" type="button"  href="javascript:void(0)" class="text-white collapsed btn btn-info btn-danger" style="border-radius: 2px !important; max-width: 3em; margin-right: 3px !important"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;</a>
                            <a  title="Filter" type="button" id="filter"  href="javascript:void(0)" class="text-white collapsed btn btn-info" style="border-radius: 2px !important; max-width: 3em; margin-right: 3px !important"><i class="fa-solid fa-magnifying-glass"></i>&nbsp;</a>
                            {{-- </div> --}}
                        </div>


                    </div>

                </form>

            </div>


    </div>


        <div class="row">
            <div class="col-md-12">
                <div class="table-container-scroll">
                    <table id="table" class="table table-striped compact table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                        <thead>
                            <tr>
                              <th style="min-width: 10rem">Full Name</th>
                              <th style="min-width: 15rem">Roles</th>
                              <th>Email</th>
                              <th style="min-width: 10rem">Phone</th>
                              <th>Username</th>
                              <th style="min-width: 10rem">Address</th>
                              <th style="min-width: 10rem">Created By</th>
                              <th style="min-width: 7rem">Action</th>
                            </tr>
                        </thead>
                    </table>
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
                    <h4 class="modal-title">Add User </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="cform">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" id="name" name="name"  class="form-control" placeholder="eg Teacher">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="description" id="description"  class="form-control" placeholder="">
                            </div>
                        </div>
                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn btn-sm" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn"  class="btn btn-primary btn-sm">Submit</a>
                </div>
            </div>
        </div>
    </div>



    {{-- end --}}


    @section('scripts')



    <script>

    let form = document.getElementById('cform')

    $(document).ready(function(){

        spark()

        let datatable = $('#table').DataTable({
        processing: true,
        serverSide: true,
        pageLength:50,
        ajax:{

        url : '{{ route('users.management.datatable') }}',
        data: function (d) {

        d.search = $('#table_filter').find('input').val(),
        d.role_id = $('#role').val(),
        d.class_id = $('#class').val(),
        d.stream_id = $('#stream').val(),
        d.house_id = $('#house').val(),
        d.religion_id = $('#religion').val(),
        d.religion_sect = $('#sect').val()

        },

        },
        search: {
        "regex": true
    },



        columns:[
        {data: 'full_name', name:'full_name'},
        {data: 'roles', name:'roles'},
        {data: 'email', name:'email'},
        {data: 'phone', name:'phone'},
        {data: 'username', name:'username'},
        {data: 'address', name:'address'},
        {data: 'created_by', name:'created_by'},
        {data:'action', name:'action', orderable:false, searchable:false}
        ],
        initComplete: function(settings, json) {
            unspark();
        },





        "columnDefs": [
        // { className: " text-right font-weight-bold", "targets": [ 1 ] },
        // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
        // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
      ],

      drawCallback(){

        unspark();
        $('#table_filter').keyup(function(){
            datatable.draw();
        })
    }

        });

    // delete user
    $('#table').on('click', '.delete', function() {
        
        var uuid = $(this).data('uuid');
        var confirmDelete = confirm('Are you sure you want to delete?');
        
        if (confirmDelete) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{ route('users.management.destroy') }}',
                type: 'DELETE',
                data: { uuid: uuid },
                headers: {
                    'X-CSRF-TOKEN': csrfToken 
                },
                success: function(response) {
                    console.log(response);

                    if (response.state === 'done') {
                        
                        datatable.draw();
                        alert('User deleted successfully.');
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    });


        let isAccordionOpen = localStorage.getItem('accordionState') === 'open';
        $('#accordion1').toggleClass('show', isAccordionOpen);

        $('#accordion').on('click', function() {
            $('#accordion1').toggleClass('show');
            let newState = $('#accordion1').hasClass('show') ? 'open' : 'closed';
            localStorage.setItem('accordionState', newState);
        });

        $('.btn-danger').on('click', function() {
            clearForm(form)
            datatable.draw()

        });

        


    })





/* ADD */




    </script>
@endsection
@endsection







