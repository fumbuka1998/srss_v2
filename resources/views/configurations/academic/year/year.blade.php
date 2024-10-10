@extends('layout.index')



@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">ACADEMIC YEARS</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a>
            <span class="breadcrumb-item active mr-3">Academic Years</span>
        </div>
    </div>
</div>
@endsection


@section('body')
    <style>
        .select2-container {
            min-width: 27rem;
        }

        .chosen-select-single {
            display: flex;
            flex-direction: column;
        }
    </style>



   <div class="card">

    <div class="card-body">


        <div class="row">
            <div class="col-md-12 mt-3 mb-2">
                <div class="float-right">
                    <a href="javascript:void(0)" data-toggle="refresh" class="btn btn-warning btn-sm"><i class="ion-android-refresh"></i></a>
                    <a href="javascript:void(0)" data-toggle="expand" class="btn btn-success btn-sm"><i class="ion-android-expand"></i></a>
                    <a href="javascript:void(0)" title="excel" onclick="generateFile('excel')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                    <a href="javascript:void(0)" title="pdf" onclick="generateFile('pdf')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>&nbsp;Pdf</a>
                    @if (auth()->user()->hasRole('Admin'))
                    <a href="javascript:void(0)" title="new academic year" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;New Academic Year</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="datatable-dashv1-list custom-datatable-overright col-md-12">
                <table id="table" class="table compact table-striped table-bordered table-sm"
                    style="width: 100%; table-layout: inherit">
                    <thead>
                        <tr>
                            <th class="color">Year</th>
                            <th class="color">Start</th>
                            <th class="color">End</th>
                            <th class="color">Status</th>
                            <th class="color">Created By</th>
                            <th class="color">Action</th>
                        </tr>
                    </thead>
                </table>


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
                    <h4 class="modal-title">Add Academic Year</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="acy-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="academic_year" id="academic_year" class="form-control"
                                        placeholder="eg 2023">
                                    <input type="hidden" name="uuid" id="uuid">
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control select2s" id="status">
                                        <option value="">Select</option>
                                        <option value="open">Open</option>
                                        <option value="closed">Closed</option>
                                        <option value="not started">Not Started</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date </label>
                                <input style="min-width: 100%" type="date" required class="form-control" id="start" name="start" value="{{ date('Y-m-d') }}" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end">End Date</label>
                                    <input  type="date" required class="form-control" id="end" name="end" value="{{ date('Y-m-d') }}" />
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
    </div>



    {{-- end --}}


@section('scripts')
    <script>

$('#start').change(function(){

let date_val = $(this).val();
if (date_val > $('#end').val()) {
    $('#end').val(date_val)
}
$('#end').attr('min',date_val);

})


        let form = document.getElementById('acy-form');
        /* DATATABLE */
        let datatable = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('academic.years.datatable') }}',
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'start',
                    name: 'start'
                },
                {
                    data: 'end',
                    name: 'end'
                },
                {
                    data: 'status',
                    name: 'status'
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
                    let url = "{{ route('academic.years.destroy') }}"
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
                    console.log(uuid);
                    let url = '{{ route('academic.years.edit') }}'
                    $.ajax({

                        url: url,
                        method: "POST",
                        data: {
                            uuid: uuid
                        },
                        beforeSend: function(xhr) {
                            clearForm(form)
                            xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                'meta[name="csrf-token"]').attr('content'));
                        },
                        success: function(res) {

                            $('#uuid').val(res.year.uuid);
                            $('#academic_year').val(res.year.name);
                            $('#status').val(res.year.status).trigger('change');
                            $('#start').val(res.from).trigger('change');
                            $('#end').val(res.to).trigger('change');
                            $('#academic_modal').modal('show');

                            unspark()

                            if (res.state == 'done') {
                                datatable.draw();

                            }
                        },
                        error: function(res) {

                            unspark()

                        }

                    })

                })

            }

        });


        /* END */



        /* POPUP MODAL  */

        $('#add').click(function() {

            $('#academic_modal').modal('show');

        })


        /* END */


        /* FORM SUBMIT */

        $('#sbmt-btn').click(function() {

            let form_data = new FormData($('#acy-form')[0]);

            $.ajax({
                url: "{{ route('academic.years.store') }}",
                processData: false,
                contentType: false,
                method: 'POST',
                data: form_data,
                beforeSend: function() {
                    showLoader();
                },
                success: function(res) {
                    hideLoader();
                    if (res.state == 'done') {
                        $('#loader-container').show();
                        $('#academic_modal').modal('hide')
                        datatable.draw();

                    }
                },
                error: function(res) {


                }

            })

            // console.log(form_data)


        })
    </script>
@endsection
@endsection
