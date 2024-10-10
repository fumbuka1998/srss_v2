@extends('layout.index')


@section('top-bread')
    <div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
        <div class="row">
            <div class="ml-3 pd-t-5 pd-b-5">
                <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">EXAMS SCHEDULE</h1>
            </div>
            <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
                <a class="breadcrumb-item" href="{{ route('dashboard') }}"><i class="icon ion-ios-home-outline"></i>
                    Dashboard</a>
                <span class="breadcrumb-item active mr-3">Exams Schedule</span>
            </div>
        </div>
    </div>
@endsection

@section('body')

    <style>
        .datatable-btns {
            margin-bottom: 0.5rem !important;
            text-align: right;

        }

        #compactTable_wrapper {
            overflow-x: auto;
        }

        #compactTable thead th:nth-child(-n+2) {
            position: -webkit-sticky;
            position: sticky;
            left: 0;
            z-index: 1;
            background-color: #f9f9f9;
        }
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
                            <a href="javascript:void(0)" title="new class" id="add" type="button"
                                class=" btn btn-info btn-sm"
                                style="border-radius: 2px !important; margin-right: 3px !important"><i
                                    class="fa fa-plus-circle"></i>&nbsp;New Exam Schedule</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table id="compactTable" class="display table-bordered compact responsive nowrap" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Academic Year</th>
                                <th>Semester</th>
                                <th>Exam Name </th>
                                <th>Start date</th>
                                <th>End Date</th>
                                <th>Marking From</th>
                                <th>Marking To</th>
                                <th>Classes</th>
                                <th>Subjects</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>Academic Year</th>
                                <th>Semester</th>
                                <th>Exam Name </th>
                                <th>Start date</th>
                                <th>End Date</th>
                                <th>Marking From</th>
                                <th>Marking To</th>
                                <th>Classes</th>
                                <th>Subjects</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
                    <h4 class="modal-title">Create Schedule </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                    <input type="hidden" name="csts" id="csts" value="{{ $csts }}">

                </div>

                <div class="modal-body">
                    <form action="#" id="examForm">

                        <input type="hidden" name="class_streams" id="class_streams">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Academic Year</label>

                                    <select style="width: 100%" class="select2s form-control" id="academic_year"
                                        name="academic_year">
                                        @foreach ($academic_years as $year)
                                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                                        @endforeach
                                    </select>

                                    <input type="hidden" name="uuid" id="uuid">
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Semester</label>
                                    <select style="width: 100%" class="select2s form-control" id="semester"
                                        name="semester">
                                        @foreach ($semesters as $semester)
                                            <option value="{{ $semester->id }}"> {{ $semester->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Exam Type</label>

                                    <select style="width: 100%" class="select2s form-control" id="exam_type"
                                        name="exam_type">
                                        @foreach ($exam_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" required class="form-control" id="exam_start" name="exam_start"
                                        value="{{ date('Y-m-d') }}" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" required class="form-control" id="end" min=""
                                        name="end" value="{{ date('Y-m-d') }}" />
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Marking Window Open</label>
                                    <input type="date" required class="form-control" id="mark_start"
                                        name="mark_start" value="{{ date('Y-m-d') }}" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Marking Window Close</label>
                                    <input type="date" required class="form-control" id="mark_end" min=""
                                        name="mark_end" value="{{ date('Y-m-d') }}" />
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 1rem;">
                                <div class="form-group">
                                    <label for="">Grading Profile</label>
                                    <select style="width: 100%;" class="select2s form-control" name="grade_group"
                                        id="grade_group">
                                        <option value=""></option>
                                        @foreach ($grade_profiles as $profile)
                                            <option value="{{ $profile->id }}">{{ $profile->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Subjects</label>
                                    <select style="width: 100%" class="select2s form-control" multiple="multiple"
                                        name="subjects[]" id="subject">
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Classes</label>
                                    <select style="width: 100%" class="select2s form-control" multiple="multiple"
                                        name="classes[]" id="class">

                                        @foreach ($class_streams as $class_stream)
                                            <option data-stream-id="{{ $class_stream['stream_id'] }}"
                                                value="{{ $class_stream['class_id'] }}">{{ $class_stream['name'] }}
                                            </option>
                                        @endforeach

                                        {{-- @endforeach --}}

                                        {{-- @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{  $class->name  }}</option>

                                @endforeach --}}
                                    </select>
                                </div>
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




    {{-- THIS MODAL --}}


    {{-- <div class="modal fade" tabindex="-1" id="axxcademic_modal" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header card-header">
                    <h5 class="modal-title">
                        Create Schedule
                    </h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">

                         <form action="#" id="examForm">

                            <input type="hidden" name="class_streams" id="class_streams">

                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Academic Year</label>

                                        <select style="width: 100%" class="select2s form-control" id="academic_year" name="academic_year">
                                            @foreach ($academic_years as $year)
                                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                                            @endforeach
                                         </select>

                                        <input type="hidden" name="uuid" id="uuid">
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Semester</label>
                                        <select style="width: 100%" class="select2s form-control" id="semester" name="semester">
                                            @foreach ($semesters as $semester)
                                            <option value="{{ $semester->id }}"> {{ $semester->name }}</option>
                                            @endforeach
                                         </select>
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Exam Type</label>

                                                <select style="width: 100%" class="select2s form-control" id="exam_type" name="exam_type">
                                                    @foreach ($exam_types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                    </div>
                            </div>

                                <div class="col-md-12" style="margin-top: 1rem;">
                                    <div class="form-group data-custon-pick data-custom-mg" id="data_5">
                                        <label>Start/End Date Range</label>
                                        <div class="input-daterange input-group" id="datepicker">
                                            <input style="min-width: 27rem" type="text"  required class="form-control" id="exam_start" name="exam_start" />
                                            <span class="input-group-addon">to</span>
                                            <input style="min-width: 27rem" type="text" required class="form-control" id="end" name="exam_end"  />
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12" style="margin-top: 1rem;">
                                    <div class="form-group data-custon-pick data-custom-mg" id="data_5">
                                        <label>Marking Window Date Range</label>
                                        <div class="input-daterange input-group" id="datepicker">
                                            <input style="min-width: 27rem" type="text"  required class="form-control" id="mark_start" name="mark_start" />
                                            <span class="input-group-addon">to</span>
                                            <input style="min-width: 27rem" type="text" required class="form-control" id="mark_end" name="mark_end" />
                                        </div>
                                    </div>
                                </div>

                            <div class="col-md-12" style="margin-top: 1rem;">
                                <div class="form-group">
                                    <label for="">Grading Profile</label>
                                    <select class="selectpicker form-control" id="number" data-container="body" data-live-search="true" title="Select a number" data-hide-disabled="false">
                                        <option value=""></option>
                                        @foreach ($grade_profiles as $profile)
                                            <option value="{{$profile->id}}">{{ $profile->name }}</option>
                                        @endforeach

                                    </select>

                                </div>
                        </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Subjects</label>
                                    <select style="width: 100%" class="select2s form-control" multiple="multiple" name="subjects[]" id="subject">
                                        @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{   $subject->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Classes</label>
                                    <select style="width: 100%" class="select2s form-control" multiple="multiple" name="classes[]" id="class">

                                        @foreach ($class_streams as $class_stream)

                                        <option data-stream-id="{{ $class_stream['stream_id']  }}" value="{{ $class_stream['class_id'] }}">{{  $class_stream['name']  }}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>

                                </div>
                        </form>


                    </div>
                <div class="modal-footer bg-light">
                    <span class="sub-text">Modal Footer Text</span>
                </div>
            </div>
        </div>
    </div> --}}


    {{-- END MODAL --}}



    {{-- end --}}


@section('scripts')
    <script>
        $('#mark_start').change(function() {

            let date_val = $(this).val();
            if (date_val > $('#mark_end').val()) {
                $('#mark_end').val(date_val)
            }
            $('#mark_end').attr('min', date_val);

        })


        $('#exam_start').change(function() {
            // alert('clicked')
            let date_val = $(this).val();
            if (date_val > $('#end').val()) {
                $('#end').val(date_val)
            }
            $('#end').attr('min', date_val);

        })


        $('#academic_year').change(function() {

            let id = $(this).val();
            console.log(id)

            $.ajax({

                beforeSend: function(xhr) {
                    showLoader();
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },

                url: '{{ route('links.terms') }}',
                data: {
                    id: id
                },
                type: 'POST',
                success: function(res) {
                    hideLoader();
                    $('#semester').html(res)
                },
                error: function(res) {
                    hideLoader();
                }
            })

        })


        let form = document.getElementById('examForm');

        let datatable = $('#compactTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: '{{ route('exams.schedule.index.datatable') }}',

            columns: [{
                    data: 'academic_year_id',
                    name: 'academic_year_id',
                    width: "400px"
                },
                {
                    data: 'semester_id',
                    name: 'semester_id',
                    width: "400px"
                },
                {
                    data: 'exam_id',
                    name: 'exam_id',
                    width: "400px"
                },
                {
                    data: 'start_from',
                    name: 'start_from'
                },
                {
                    data: 'end_on',
                    name: 'end_on'
                },
                {
                    data: 'marking_from',
                    name: 'marking_from'
                },
                {
                    data: 'marking_to',
                    name: 'marking_to'
                },
                {
                    data: 'classes',
                    name: 'classes',
                    width: "400px"
                },
                {
                    data: 'subjects',
                    name: 'subjects',
                    width: "400px"
                },
                {
                    data: 'status',
                    name: 'status',
                    width: "400px"
                },
                {
                    data: 'created_by',
                    name: 'created_by',
                    width: "400px"
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: "400px"
                }
            ],
            // Disable default sorting
            "order": [],
            "columnDefs": [{
                    className: " text-right ",
                    "targets": [1]
                },
                {
                    className: "text-success text-right ",
                    "targets": [3, 5],
                    width: "400px"
                },
                {
                    className: "text-danger text-right ",
                    "targets": [4, 6],
                    width: "400px"
                }
            ],

            drawCallback: function() {

                $('.delete').click(function() {
                    let uuid = $(this).data('uuid');
                    let url = "{{ route('exams.schedule.index.destroy') }}"
                    let method = "DELETE"
                    ajaxQuery({
                        url: url,
                        method: method,
                        uuid: uuid
                    })
                });



                $('.edit').click(function () {
                    let uuid = $(this).data('uuid');
                    let url = '{{ route('exams.schedule.index.edit') }}'
                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                            uuid: uuid
                        },
                        beforeSend: function (xhr) {
                            clearForm(form)
                            showLoader();
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        },
                        success: function (res) {
                            // console.log(res.ac_year);
                            // Populate fields with data from the response
                            $('#uuid').val(res.exam_schedule.uuid);
                            $('#academic_year').val(res.ac_year);
                            $('#semester').val(res.schdle_semester);
                            $('#exam_type').val(res.exam_type).trigger('change');
                            $('#exam_start').val(res.s_date);
                            $('#end').val(res.e_date);
                            $('#mark_start').val(res.mark_w_o);
                            $('#mark_end').val(res.mark_w_c);
                            $('#grade_group').val(res.grade_prfle).trigger('change');

                            // Assuming that your subjects are stored as an array in res.subjct_scheduled
                            // You may need to adjust this part based on your actual data structure
                            $('#subject').val(res.subjct_scheduled.map(subject => subject.id)).trigger('change');

                            // Assuming that your classes are stored as an array in res.classes
                            // You may need to adjust this part based on your actual data structure
                            if (Array.isArray(res.classes)) {
                                $('#class').val(res.classes.map(cls => cls.class_id)).trigger('change');
                                // Display classes as buttons
                                $('#class_streams').html(res.classes.map(cls => '<button style="padding: 2px 5px;border-radius: 5px; margin-top:1rem;  color: darkblue; border: 1px dotted rgba(0, 0, 139, 0.596);"> ' + cls.class_name + ' ' + cls.stream_name + ' </button> &nbsp;'));
                            }

                            $('#academic_modal').modal('show');
                            hideLoader();

                            if (res.state == 'done') {
                                datatable.draw();
                            }
                        },
                        error: function (res) {
                            // Handle error if needed
                        }
                    })
                });



            }

        });



        /* ADD */

        $('#add').click(function() {

            clearForm(form)

            $.ajax({

                url: '{{ route('academic.exams.preliminaries.fetch') }}',

                beforeSend: function(xhr) {
                    clearForm(form)
                    showLoader();
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(res) {

                    let subjectElement = document.querySelector('select[name="subjects[]"]');
                    let classElement = document.querySelector('select[name="classes[]"]');
                    let class_preselected = res.classes;
                    let subjects_preselected = res.subjects;

                    for (var i = 0; i < subjectElement.options.length; i++) {
                        let = subjectElement.options[i].setAttribute('selected', true);
                        let changeEvent = new Event('change', {
                            bubbles: true
                        });
                        subjectElement.options[i].dispatchEvent(changeEvent);
                    }


                    for (var i = 0; i < classElement.options.length; i++) {
                        let = classElement.options[i].setAttribute('selected', true);
                        let changeEvent = new Event('change', {
                            bubbles: true
                        });
                        classElement.options[i].dispatchEvent(changeEvent);
                    }

                    $('#academic_modal').modal('show');
                    hideLoader();
                },
                error: function(res) {

                    console.log(res)
                    hideLoader();

                }


            })


        })


        $('.enter').keyup(function(e) {

                if (e.keyCode == 13) {
                    let url = "{{ route('academic.exams.store') }}"
                    let method = "POST"
                    let form_data = new FormData($('#acmdform')[0]);
                    ajaxQuery({
                        url: url,
                        method: method,
                        form_data: form_data
                    })

                }

            }),


            /* FORM SUBMIT */



            $('#sbmt-btn').on('click', function(e) {
                e.preventDefault();

                let selectedClassIds = $('#class').val();
                let classElement = $('#class');

                let claxStreams = [];
                $('#class option:selected').each(function() {
                    const stream_id = $(this).data('stream-id');
                    const class_id = $(this).val();
                    claxStreams.push({
                        class_id: class_id,
                        stream_id: stream_id
                    });
                });
                $('#class_streams').val(JSON.stringify(claxStreams));

                let url = "{{ route('exams.schedule.index.store') }}"
                let method = "POST"
                let form_data = new FormData($('#examForm')[0]);
                ajaxQuery({
                    url: url,
                    method: method,
                    form_data: form_data
                })

            });
    </script>
@endsection
@endsection
