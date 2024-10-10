@extends('layout.index')

@section('body')

    <style>

    </style>


    <div class="card">
        <div class="card-header">

        </div>

        <div class="card-body">
            @include('results.nav')

            <input type="hidden" name="xdl_uuid" id="xdl_uuid">

            <table id="compactTable" class="display table  compact responsive nowrap" style="width: 100%">
                <thead>
                    <thead>
                        <tr>
                            <th class="color">Year</th>
                            <th class="color">Semester</th>
                            <th class="color">Class</th>
                            <th class="color">Stream</th>
                            <th class="color">Exam</th>
                            <th class="color">Subject</th>
                            <th class="color">Marking Closes In</th>
                            <th class="color">Action</th>
                        </tr>
                    </thead>
                </thead>

                <tfoot>
                    <tr>
                        <th>Year</th>
                        <th>Semester</th>
                        <th>Class</th>
                        <th>Stream</th>
                        <th>Exam</th>
                        <th>Subject</th>
                        <th>Marking Closes In</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>


        </div>
    </div>


@section('scripts')
    <script>
        let datatable = $('#compactTable').DataTable({

            responsive: true,
            scrollX: true,

            language: {
                searchPlaceholder: 'Search...',
                sSearch: ''
            },

            processing: true,
            serverSide: true,
            pageLength:50,


            ajax: '{{ route('exams.waiting.marking.datatable') }}',
            columns: [{
                    data: 'academic_year_id',
                    name: 'academic_year_id'
                },
                {
                    data: 'semester_id',
                    name: 'semester_id'
                },
                {
                    data: 'classes',
                    name: 'classes'
                },
                {
                    data: 'stream_name',
                    name: 'stream_name'
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
                    data: 'marking_ends',
                    name: 'marking_ends'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }


            ],
            "order": [],
            "columnDefs": [
                // { className: " text-right font-weight-bold", "targets": [ 1 ] },
                // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
                // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
            ],

            drawCallback: function(settings) {



                $(document).ready(function() {

                    $('.enter_marks').click(function(e) {

                        let uuid = $(this).data('uuid');
                        $('#xdl_uuid').val(uuid)
                    })


                    $('#compactTable tbody tr').each(function() {
                        const row = $(this);
                        const el = row.find('.regular_timer');
                        const countdownElement = row.find('.clock_time');
                        const btn = row.find('.enter_marks');
                        const locked = row.find('.locked');
                        const regular = row.find('.fa-regular');
                        const rowIdentifier = row.data('row-id');

                        const sql_time = el.data('marking-end');
                        const endTime = new Date(sql_time);

                        function updateCountdown() {
                            const currentTime = new Date();
                            const timeDiff = endTime - currentTime;

                            if (timeDiff <= 0) {
                                btn.addClass('d-none');
                                locked.removeClass('d-none');
                                regular.removeClass('fa-spin');

                                countdownElement.html(
                                    '<span style="color:#e36e60"> Time expired </span>');
                            } else {
                                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (
                                    1000 * 60 * 60));
                                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (
                                    1000 * 60));
                                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

                                countdownElement.html(
                                    `<span style="color:#008080"> ${days} days </span> <span style="color:#2196f3"> ${hours} hours </span> <span style="color:#36a000"> ${minutes} mins </span> <span style="color:#e36e60"> ${seconds} sec </span>`
                                );
                            }
                        }

                        // Call the function to start the countdown
                        updateCountdown();

                        // Update the countdown every second
                        setInterval(updateCountdown, 1000);
                    });
                });



                // .text('halaaa');

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
                                                icon: 'success',
                                                title: res.msg,
                                                showConfirmButton: false,
                                                timer: 2000,
                                                timerProgressBar: true,
                                                position: 'top-end', // Adjust as needed
                                                toast: true,

                                            });

                                            datatable.draw();


                                            /* start */

                                            $.ajax({
                                                url: '{{ route('results.sytem.excel.completed.marks') }}',
                                                beforeSend: function(xhr) {

                                                    xhr.setRequestHeader(
                                                        'X-CSRF-TOKEN',
                                                        $(
                                                            'meta[name="csrf-token"]'
                                                            )
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


        // js to impelment the extend the marking time
        $(document).on('click', '.locked', function() {
            var examId = $(this).data('exam-id');

            $.ajax({
                url: '{{ route('extend.marking.time') }}',
                type: 'POST',
                data: {
                    exam_id: examId
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(res) {
                    // console.log(res);
                    toast(res.msg,res.title);
                    datatable.draw();
                    // location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endsection
@endsection
