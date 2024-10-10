@extends('layout.index')

@section('body')

    <style>

    </style>

<div class="card mt-4">
    <div class="card-header">
        REPORTS
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card-body">
                @include('results.results-nav.index')

                <div class="row clearfix mt-4">

                    <input type="hidden" name="xdl_uuid" id="xdl_uuid">

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="">Academic Year</label>
                            <select name="year" class="form-control select2s" id="year" required>
                                <option value=""></option>
                                @foreach ($academic_years as $year)
                                    <option {{ $year->id == $academic_year_id ? 'selected' : '' }}
                                        value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="">Term</label>
                            <select name="term"  class="form-control select2s" id="term" required>
                                <option value=""></option>
                                @foreach ($terms as $term)
                                    <option {{ $term->id == $term_id ? 'selected' : '' }}
                                        value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="">Class</label>

                            <select name="class_id" class="form-control select2s" id="class">
                                <option value="">Select Class...</option>
                                @foreach ( $assigned as $scl)
                                <option value="{{ $scl['class_id'] }}">{{  $scl['class_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>






                    <div class="col-md-3 col-sm-12">

                        <div class="form-group">
                            <label for="">Stream/Combination</label>

                            <select name="stream_id" class="form-control select2s" id="stream">
                                <option value="">Select Class...</option>
                                @foreach ( $assigned as $scl)
                                <option value="{{ $scl['stream_id'] }}">{{  $scl['stream_name'] }}</option>
                                @endforeach

                            </select>
                        </div>

                    </div>


                    <div class="col-md-3 col-sm-12">

                        <div class="form-group">
                            <label for="">Subject</label>
                            <select name="subject" class="form-control select2s" id="subject" disabled required>
                                <option value="">Select Subject...</option>
                                @foreach ($subjects as $subject)
                                    <option {{ $subject->id == $subject_id ? 'selected' : '' }}
                                        value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Exam Type</label>
                            <select name="exam_type[]" multiple class="form-control select2s"
                                id="exam" required >
                                <option value=""></option>
                                @foreach ($exams as $exam)
                                    <option {{ $exam->id == $exam_id ? 'selected' : '' }}
                                        value="{{ $exam->id }}">{{ $exam->name }}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="report_name">Report Name:</label>
                            <select name="report_name" class="form-control select2s"
                                id="report_name" style="width: 100%" required>
                                <option value=""></option>
                                @foreach ($reports as $report)
                                    <option value="{{ $report->id }}"> {{ $report->name }} </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12" style="display: none">
                        <div class="form-group">
                            <label for="student">Student</label>
                            <select name="student" class="form-control select2s" id="student">

                            </select>
                        </div>
                    </div>

                    <div class="col-md-12" style="display: flex; justify-content: end;">
                    <span>
                        <button type="button" id="generate"
                            class="btn btn-sm btn-info">
                            <i class="fa-solid fa-magnifying-glass"></i> &nbsp;Preview
                        </button>
                    </span>
                </div>



                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="template_class" style="margin-top: 2%; background:#ffff"> </div>
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
                    <h4 class="modal-title">ACADEMIC REPORT GENERATION</h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="cform">
                        <div class="row">
                            <div class="col-md-12 mg-t-20 mg-lg-t-0">
                                <div class="custom-control custom-checkbox">
                                   <input type="checkbox" class="custom-control-input" value="1" name="ca" id="character_assesment">
                                   <label class="custom-control-label" for="character_assesment">Will this Report Have Character Assessment?</label>
                                </div>
                             </div>

                             <div class="col-md-12 mg-t-20 mg-lg-t-0 mt-3">
                                <div class="custom-control custom-checkbox">
                                   <input type="checkbox" class="custom-control-input" name="es" value="1" id="escalation">
                                   <label class="custom-control-label" for="escalation">Will This Report Be Escalated?</label>
                                </div>
                             </div>

                             <input type="hidden" id="form_type">
                    </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn btn-sm"
                        href="#">Cancel</a>
                    <a href="javascript:void(0)" id="submit_report" class="btn btn-info btn-sm">Submit</a>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal now --}}








    {{-- end --}}

@section('scripts')
    <script>
        /* SELECT2 */

        $(document).ready(function() {

            $('.select2s').select2({
                width:'100%',
                placeholder: 'Select an option',
                allowClear: true


            })
//             $(".select2").select2({
//     width: 'resolve' // need to override the changed default
// })

            $('#class').change(function() {

                let class_id = $(this).val()
                $.ajax({

                    url: '{{ route('links.streams') }}',
                    method: 'POST',
                    data: {
                        id: class_id
                    },

                    beforeSend: function(xhr) {
                        showLoader();
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },

                    success: function(res) {

                        hideLoader();

                        $('#stream').html(res.streams).trigger('change');
                        $('#subject').html(res.subjects).trigger('change');
                        $('#student').html(res.students).trigger('change');

                    },

                    error: function() {




                    }


                })



            });




            $('#year').change(function() {

                let year_id = $(this).val()

                $.ajax({

                    url: '{{ route('links.terms') }}',
                    method: 'POST',
                    data: {
                        id: year_id
                    },

                    beforeSend: function(xhr) {
                        showLoader();
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },

                    success: function(res) {
                        hideLoader();
                        $('#term').html(res).trigger('change');
                    },

                    error: function() {



                    }

                })

            })


            /* ya kwanza */
            $('body').on('click', '.generate_dynamic_multiple_report', function() {

                $('#form_type').val('multiple');
                $("#academic_modal").modal('show')


                spark()
                let form = $('body').find('#generate_dynamic_multiple_report')[0];

                let form_data = new FormData(form);

                let ca = $('#character_assesment').val()
                let es = $("#escalation").val()

                form_data.append('ca', ca);  // Append additional parameters to the FormData object
                form_data.append('es', es);
                unspark()


                // $.ajax({

                //     url: '{{ route('results.reports.generate.report.post') }}',
                //     processData: false,
                //     contentType: false,
                //     type: 'POST',
                //     data: form_data,

                //     beforeSend: function(xhr) {
                //         xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                //             'content'));
                //     },


                //     success: function(res) {

                //         console.log(res);
                //         unspark();
                //         if (res.state == 'done') {
                //             showNotification(res.msg, res.title);
                //         }

                //         // if ($.fn.DataTable.isDataTable('#table')) {
                //         // $('#table').DataTable().destroy();
                //     },
                //     error: function() {

                //         unspark();

                //     }
                //     // }

                // })

                // alert('clicked');




            })

        $('body').on('click', '.generate_dynamic_single_exam_report', function() {
        $('#form_type').val('single');
        $("#academic_modal").modal('show')
        })


            $('#submit_report').click(function(){


                        spark()

                        let form = '';

                            if ($('#form_type').val() == 'multiple') {

                                form = $('body').find('#generate_dynamic_multiple_report')[0];

                            }
                            if($('#form_type').val() == 'single'){

                                form = $('body').find('#single_exam_type_no_subject')[0];

                            }

                        let form_data = new FormData(form);
                        let ca = $('#character_assesment').val()
                        let es = $("#escalation").val()

                        form_data.append('ca', ca);  // Append additional parameters to the FormData object
                        form_data.append('es', es);

                        $.ajax({

                        url: '{{ route('results.reports.generate.report.post') }}',
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        data: form_data,
                        beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                        'content'));
                        },


                        success: function(res) {

                        console.log(res);
                        if (res.status == 'done') {
                            $("#academic_modal").modal('hide')

                            Swal.fire({
                            title: "<strong> Report Generation Success </strong>",
                            icon: "success",
                            showCloseButton: true,
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText: `
                            <i class="fa-solid fa-file"></i> Go To Generated Reports
                            `,
                            confirmButtonAriaLabel: "Go To Generated Reports",
                            cancelButtonText: `
                            <i class="fa-solid fa-rotate-left"></i> Go back
                            `,
                            cancelButtonAriaLabel: "Go back",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Redirect to "Generated Reports"
                                    window.location.href = '{{ route('results.reports.generated.reports.index')  }}';
                                }
});

                        }
                        unspark();
                        // toast(res.msg, res.title);

                        },
                        error: function() {

                        unspark();

                        }
                        // }

                        })


            })



            $('#generate').click(function() {

                spark();

                let class_id = $('#class').val()
                let stream_id = $('#stream').val()
                let subject_id = $('#subject').val()
                let exam = $('#exam').parsley()
                let year = $('#year').parsley()
                let term = $('#term').parsley()
                let report_name = $('#report_name').parsley()

                if (report_name.isValid() && exam.isValid() &&  year.isValid() && term.isValid() ) {


                            $.ajax({

                            url: '{{ route('results.reports.load') }}',
                            type: 'POST',
                            data: {
                            class_id: class_id,
                            stream_id: stream_id,
                            subject_id: subject_id,
                            exam_type: $('#exam').val(),
                            acdmcyear: $('#year').val(),
                            report_name: $('#report_name').val(),
                            semester: $('#term').val(),
                            },

                            beforeSend: function(xhr) {

                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                            },


                            success: function(res) {

                            $('.template_class').html(res.base_html);
                            unspark();


                            },

                            error: function(res) {

                            console.log(res)
                            unspark();

                            }



                            })

                }else{

                    report_name.validate()
                    exam.validate()
                    year.validate()
                    term.validate()

                    unspark()
                }
            })




            /* DATATABLE */




        })
    </script>
@endsection
@endsection
