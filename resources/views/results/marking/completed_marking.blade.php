@extends('layout.index')

@section('body')
    <style>
        .color {
            background: #069613;
            color: white;
        }
    </style>

    <div class="card mt-4">
        <div class="card-header">
            COMPLETED MARKING
        </div>

        <div class="card-body">
            @include('results.nav')

            <div class="row clearfix mt-4">
                <div class="col-md-12">
                    <table id="table" class="table compact table-striped table-bordered table-sm"
                        style="width: 100%; table-layout: inherit">
                        <thead>
                            <tr>
                                <th class="color">Academic Year</th>
                                <th class="color">Semester</th>
                                <th class="color">Class</th>
                                <th class="color">Stream</th>
                                <th class="color">Exam Type</th>
                                <th class="color">Subject</th>
                                <th class="color">Action</th>
                            </tr>
                        </thead>
                    </table>




                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}

    {{-- end --}}

@section('scripts')
    <script>
        let datatable = $('#table').DataTable({
            processing: true,
            serverSide: true,

            responsive: true,
            scrollX: true,
            pageLength:50,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: ''
            },

            ajax: '{{ route('results.sytem.complete.entry.datatable') }}',
            columns: [{
                    data: 'academic_year_id',
                    name: 'academic_year_id'
                },
                {
                    data: 'semester_id',
                    name: 'semester_id'
                },
                {
                    data: 'class_id',
                    name: 'class_id'
                },
                {
                    data: 'stream_id',
                    name: 'stream_id'
                },
                {
                    data: 'exam_id',
                    name: 'exam_id'
                },
                {
                    data: 'subject_id',
                    name: 'subject_id'
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
                    let url = "{{ route('academic.classes.destroy') }}"
                    let method = "DELETE"
                    ajaxQuery({
                        url: url,
                        method: method,
                        uuid: uuid
                    })
                });

                $('.edit').click(function() {

                    let uuid = $(this).data('uuid');
                    let url = '{{ route('academic.classes.edit') }}'
                    $.ajax(

                        {
                            url: url,
                            method: "POST",
                            data: {
                                uuid: uuid
                            },
                            beforeSend: function(xhr) {
                                showLoader();
                                xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                    'meta[name="csrf-token"]').attr('content'));
                            },
                            success: function(res) {
                                $('#uuid').val(res.uuid);
                                $('#name').val(res.name);
                                $('#code').val(res.code);
                                $('#education_level_id').removeClass('select2_demo_3').val(res
                                    .education_level_id).addClass('select2_demo_3').trigger(
                                    'change');
                                $('#capacity').val(res.capacity)


                                $('#academic_modal').modal('show');

                                hideLoader();

                                if (res.state == 'done') {
                                    datatable.draw();

                                }
                            },
                            error: function(res) {


                            }

                        })

                })


                $('.print_og').click(function(e) {
                    e.preventDefault();

                    console.log($(this).data('academic_year_id'));

                    var academic_year_id = $(this).data('academic_year_id');
                    var semester_id = $(this).data('semester_id');
                    var class_id = $(this).data('class_id');
                    var stream_id = $(this).data('stream_id');
                    var exam_id = $(this).data('exam_id');
                    var subject_id = $(this).data('subject_id');

                    
                    // Ajax request to fetch print preview content
                    $.ajax({
                        url: $(this).attr('href'),
                        type: 'GET',
                        data: {
                            academic_year_id: academic_year_id,
                            semester_id: semester_id,
                            class_id: class_id,
                            stream_id: stream_id,
                            exam_id: exam_id,
                            subject_id: subject_id
                        },
                        success: function(responseData) {
                            console.log(responseData);
                            // Handle success - render print preview
                            // $('#printPreviewContent').html(responseData.print_preview);
                            $('#printPreviewContent').html('<embed src="data:application/pdf;base64,' + responseData.print_preview + '" type="application/pdf" width="100%" height="600px"/>');


                            
                            // Open print dialog
                            window.print();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            // Handle error if necessary
                        }
                    });
                });

        








                /* RETURN TO INCOMPLETE */

                $('.revert').click(function() {

                    let uuid = $(this).data('uuid');
                    let url = '{{ route('results.complete.revert') }}';

                    let year_id = $(this).data('acdmc');
                    let semester_id = $(this).data('smst');
                    let class_id = $(this).data('cls');
                    let stream_id = $(this).data('strm');
                    let exam_type = $(this).data('extype');
                    let subject_id = $(this).data('sbjct_id');

                    swal.fire({
                        title: 'confirm',
                        text: 'Are you sure?',
                        type: 'warning',
                        confirmButtonText: 'OK'
                    }).then(function(result) {
                        if (result.value) {
                            swal.showLoading();

                            $.ajax(

                                {
                                    url: url,
                                    method: "POST",
                                    data: {
                                        year_id: year_id,
                                        semester_id: semester_id,
                                        class_id: class_id,
                                        stream_id: stream_id,
                                        exam_type: exam_type,
                                        subject_id: subject_id
                                    },
                                    beforeSend: function(xhr) {
                                        showLoader();
                                        xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                            'meta[name="csrf-token"]').attr(
                                            'content'));
                                    },
                                    success: function(res) {
                                        hideLoader();

                                        if (res.state == 'done') {


                                            Swal.fire({
                                                icon: 'success', // 'success', 'error', 'warning', etc.
                                                title: res.msg,
                                                showConfirmButton: false,
                                                timer: 2000, // Automatically close after 2 seconds (similar to Toastr)
                                                timerProgressBar: true,
                                                position: 'top-end', // Adjust as needed
                                                toast: true,
                                                //   background: '#f8f9fa', // Adjust to match your app's design
                                                // Other customization options...
                                            });

                                            datatable.draw();


                                            /* start */

                                            $.ajax({
                                                url: '{{ route('results.sytem.excel.completed.marks') }}',
                                                beforeSend: function(xhr) {

                                                    xhr.setRequestHeader(
                                                        'X-CSRF-TOKEN',
                                                        $(
                                                            'meta[name="csrf-token"]')
                                                        .attr('content')
                                                        );
                                                },

                                                success: function(res) {
                                                    $('.completed').text(
                                                        res)
                                                },
                                                error: function(res) {

                                                    console.log(res)

                                                }

                                            });

                                            /* end */

                                            /* start */

                                            $.ajax({

                                                url: '{{ route('results.sytem.excel.incomplete.marks') }}',

                                                success: function(res) {
                                                    $('.incomplete').text(
                                                        res)
                                                },
                                                error: function(res) {
                                                    console.log(res)
                                                }
                                            })

                                            /* end */


                                        }
                                    },
                                    error: function(res) {
                                        console.log(res)
                                    }

                                })
                        }


                    });

                })

            }

        });



        /* ADD */

        $('#add').click(function() {

            $('#academic_modal').modal('show');

        })

        $('#sbmt-btn').click(function() {

            let form_data = new FormData($('#cform')[0]);
            let url = '{{ route('academic.classes.store') }}'
            let method = "POST"
            ajaxQuery({
                url: url,
                method: method,
                form_data: form_data
            })

        })


        /* END */
    </script>
@endsection
@endsection
