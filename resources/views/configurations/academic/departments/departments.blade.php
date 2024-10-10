@extends('layout.index')

@section('top-bread')
    <div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
        <div class="row">
            <div class="ml-3 pd-t-5 pd-b-5">
                <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">DEPARTMENTS</h1>
            </div>
            <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
                <a class="breadcrumb-item" href="{{ route('dashboard') }}"><i class="icon ion-ios-home-outline"></i>
                    Dashboard</a>
                <a class="breadcrumb-item" href="{{ route('academic.index') }}">Academic</a>
                <span class="breadcrumb-item active mr-3">Departments</span>
            </div>
        </div>
    </div>
@endsection

@section('body')

    <style>

    </style>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mt-3 mb-2">
                    <div class="float-right">
                        <a href="javascript:void(0)" data-toggle="refresh" class="btn btn-warning btn-sm"><i
                                class="ion-android-refresh"></i></a>
                        <a href="javascript:void(0)" data-toggle="expand" class="btn btn-success btn-sm"><i
                                class="ion-android-expand"></i></a>
                        <a href="javascript:void(0)" title="excel" onclick="generateFile('excel')"
                            style="border-radius: 2px !important; margin-right: 3px !important"
                            class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                        <a href="javascript:void(0)" title="pdf" onclick="generateFile('pdf')"
                            style="border-radius: 2px !important; margin-right: 3px !important"
                            class="btn btn-warning btn-sm"><i class="fa fa-print"></i>&nbsp;Pdf</a>
                        @if (auth()->user()->hasRole('Admin'))
                            <a href="javascript:void(0)" title="new Department" id="add" type="button"
                                class=" btn btn-info btn-sm"
                                style="border-radius: 2px !important; margin-right: 3px !important"><i
                                    class="fa fa-plus-circle"></i>&nbsp;New Department</a>
                        @endif
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <table id="table" class="table table-striped table-bordered table-sm"
                        style="width: 100%; table-layout: inherit">
                        <thead>
                            <tr>
                                <th class="color">Department</th>
                                <th class="color">Code</th>
                                <th class="color">Hod Name</th>
                                <th class="color">Created By</th>
                                <th class="color" style="min-width:5em">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tab-content">
                <div id="inbox" class="tab-pane fade in animated zoomInDown custom-inbox-message shadow-reset active">
                    <div class="mail-title inbox-bt-mg">
                        <h2>Departments</h2>
                        <div class="view-mail-action view-mail-ov-d-n">
                            {{-- <a href="#"><i class="fa fa-reply"></i> Reply</a> --}}
                            <a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i>
                                Print</a>
                            <a href="javascript:void(0)" id="add" class="btn btn-custon-four btn-primary btn-xs"><i
                                    class="fa fa-plus"></i>Add</a>
                        </div>
                    </div>
                    <div class="datatable-dashv1-list custom-datatable-overright">


                    </div>
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
                    <h4 class="modal-title">Add Department </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="dptsform">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input id="name" type="text" name="name" class="form-control enter"
                                        placeholder="eg  Mathematics">
                                    <input type="hidden" name="uuid" id="uuid">
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Code</label>
                                    <input id="code" type="text" name="code" class="form-control enter"
                                        placeholder="">
                                </div>
                            </div>
                      
                        
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Assign HOD</label>
                                    <select name="hod_id" class="form-control select2s" id="hod_id">
                                        <option value="">Select</option>
                                        @foreach ($hods as $hod )
                                        <option value="{{ $hod->id }}">{{ $hod->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                     


                    </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn"
                        href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn" class="btn btn-info">Submit</a>
                </div>
            </div>
        </div>




        {{-- end --}}


    @section('scripts')
        <script>
            let datatable =
                $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    scrollX: true,
                    ajax: '{{ route('academic.departments.datatable') }}',
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data:'hod_id',
                            name:'hod_name'
                        },
                        {
                            data: 'created_by',
                            name: 'created_by'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    "columnDefs": [
                        // { className: " text-right font-weight-bold", "targets": [ 1 ] },
                        // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
                        // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
                    ],

                    drawCallback: function() {

                        $('.delete').click(function() {
                            let uuid = $(this).data('uuid');
                            let url = "{{ route('academic.departments.destroy') }}"
                            let method = "DELETE"
                            ajaxQuery({
                                url: url,
                                method: method,
                                uuid: uuid
                            })
                        });

                        $('.edit').click(function() {

                            spark()
                            let uuid = $(this).data('uuid');
                            let url = '{{ route('academic.departments.edit') }}'
                            $.ajax({

                                url: url,
                                method: "POST",
                                data: {
                                    uuid: uuid
                                },
                                beforeSend: function(xhr) {

                                    xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                        'meta[name="csrf-token"]').attr('content'));
                                },
                                success: function(res) {
                                    $('#uuid').val(res.uuid);
                                    $('#name').val(res.name);
                                    $('#code').val(res.code);
                                    $('#hod_id').removeClass('select2_demo_3').val(res.hod_id).addClass('select2_demo_3').trigger('change');


                                    $('#academic_modal').modal('show');

                                    unspark();

                                    if (res.state == 'done') {
                                        datatable.draw();

                                    }
                                },
                                error: function(res) {

                                    unspark();

                                }

                            })




                        })

                    }

                });



            /* ADD */

            $('#add').click(function() {

                $('#academic_modal').modal('show');

            })

            $('.enter').keyup(function(e) {

                    if (e.keyCode == 13) {
                        let url = "{{ route('academic.departments.store') }}"
                        let method = "POST"
                        let form_data = new FormData($('#dptsform')[0]);
                        ajaxQuery({
                            url: url,
                            method: method,
                            form_data: form_data
                        })

                    }

                }),



                /* FORM SUBMIT */

                $('#sbmt-btn').on('click', function(e) {
                    let url = "{{ route('academic.departments.store') }}"
                    let method = "POST"
                    let form_data = new FormData($('#dptsform')[0]);
                    ajaxQuery({
                        url: url,
                        method: method,
                        form_data: form_data
                    })

                });
        </script>
    @endsection

@endsection
