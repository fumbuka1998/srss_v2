@extends('layout.index')

@section('top-bread')
    <div class="pageheader pd-y-25 mt-3 mb-3" style="background-color:white;">
        <div class="row">
            <div class="ml-3 pd-t-5 pd-b-5">
                <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header"> STUDENTS LIST</h1>
            </div>
            <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
                <a class="breadcrumb-item" href="{{ route('dashboard') }}"><i class="icon ion-ios-home-outline"></i>
                    Dashboard</a>
                <span class="breadcrumb-item active mr-3">Active Students</span>
            </div>
        </div>
    </div>
@endsection


@section('body')

    <style>
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

        .s1 {

            background: #069613 !important;
            color: #ffffff;

        }
    </style>
    <div class="card mb-4 shadow-1">
        <div class="card-body collapse show" id="collapse4">
            <div class="row clearfix">
                <div class="col-md-12 mt-3 mb-2">
                    <div class="float-left">
                        <a title="Show Filters" id="accordion" class="text-dark collapsed btn btn-info btn-sm"
                            style="border-radius: 2px !important; margin-right: 3px !important"><i
                                class="fa-solid fa-filter"></i>&nbsp;Filters</a>
                    </div>

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

                        @if (auth()->user()->getIsClassTeacher() ||
                                auth()->user()->hasRole('Admin'))
                            <a href="{{ route('students.registration.single') }}" title="new invoice" id="register"
                                type="button" class=" btn btn-info btn-sm"
                                style="border-radius: 2px !important; margin-right: 3px !important"><i
                                    class="fa fa-plus-circle"></i>&nbsp;New Student</a>
                        @endif


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
                                            <label for="class">Class</label>
                                            <select name="class" id="class" class="form-control select2s">
                                                <option value="">Filter By Class</option>
                                                @foreach ($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="stream">Stream</label>
                                            <select name="stream" id="stream" class="form-control select2s">
                                                <option value="">Filter By Stream</option>
                                                @foreach ($streams as $stream)
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
                                                @foreach ($religions as $religion)
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
                                                @foreach ($religion_sects as $sect)
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
                                                @foreach ($clubs as $club)
                                                    <option value="{{ $club->id }}"> {{ $club->name }} </option>
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
                                    </div>

                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="reg_from">Registration From</label>
                                            <input type="date" name="reg_from" id="reg_from" class="form-control" max="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="reg_to">Registration To</label>
                                            <input type="date" name="reg_to" id="reg_to" class="form-control" max="{{ date('Y-m-d') }}">
                                        </div>
                                    </div> 


                                </div> 

                            </div>

                            <div class="col-md-2"
                                style="display: flex; flex-direction:column; justify-content:space-around; align-items:center;">
                                {{-- <div class="form-group"> --}}
                                <a title="Clear" type="button" href="javascript:void(0)"
                                    class="text-white collapsed btn  btn-danger"
                                    style="border-radius: 2px !important; max-width: 3em; margin-right: 3px !important"><i
                                        class="fa-solid fa-filter-circle-xmark"></i>&nbsp;</a>
                                <a title="Filter" type="button" id="filter" href="javascript:void(0)"
                                    class="text-white collapsed btn btn-info"
                                    style="border-radius: 2px !important; max-width: 3em; margin-right: 3px !important"><i
                                        class="fa-solid fa-magnifying-glass"></i>&nbsp;</a>
                                {{-- </div> --}}
                            </div>


                        </div>

                    </form>

                </div>


            </div>

            <table id="compactTable" class="display compact responsive nowrap" style="width: 100%">
                <thead>
                    <tr class="header_tr">
                        @foreach ($headers as $key => $header)
                            <th class="s1" data-field="{{ $header['name'] }}">{{ $header['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>

                {{-- <tfoot>
                    <tr>
                        @foreach ($headers as $key => $header)
                            <th data-field="{{ $header['name'] }}">{{ $header['label'] }}</th>
                        @endforeach
                    </tr>
                </tfoot> --}}
            </table>
        </div>
    </div>
    </div>


    </div>
    {{-- d.registration_date = [
        $('#reg_from').val(),
        $('#reg_to').val()
    ]; --}}


    <div class="color-switcher hide-color-switcher">
        <!--Color switcher Show/Hide button -->
        <a class="switcher-button"><i class="fa fa-cog fa-spin"></i></a>
        <!-- Color switcher title -->
        <div class="color-switcher-title">
            <span class="tx-16 text-center" style="color:#069613">Hide /Show Columns</span>
        </div>
        <!-- Colors style -->

        <div class="row">

            @foreach ($additional_headers as $index => $header)
                <div class="col-md-12">
                    <div class="custom-control custom-checkbox">
                        <input value="{{ $header['name'] }}" type="checkbox" class="custom-control-input"
                            name="{{ $header['name'] }}" id="{{ $header['name'] }}"
                            {{ $header['default'] ? 'checked' : '' }}>
                        <label class="custom-control-label" for="{{ $header['name'] }}"> {{ $header['label'] }} &nbsp;
                            <i style="color:#069613" class="fa-solid loader d-none fa-spin fa-circle-notch"></i> </label>
                    </div>
                </div>
            @endforeach

        </div>



    </div> 

    



@section('scripts')
    <script>
        var handleColorSwitcher = function() {
            $(".color-switcher .switcher-button").on('click', function() {
                $(".color-switcher").toggleClass("show-color-switcher", "hide-color-switcher", 800);
            });


            $('a.color').on('click', function() {
                var title = $(this).attr('title');
                $('#style-colors').attr('href', 'assets/css/skin/skin-' + title + '.css');
                return false;
            });
        }


        $('#class').change(function() {

            spark()
            let class_id = $(this).val()

            $.ajax(

                {
                    url: '{{ route('academic.class.streams.fetch') }}',
                    method: "POST",
                    data: {
                        id: class_id
                    },

                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(res) {

                        $('#stream').html(res)

                        unspark();

                    },
                    error: function(res) {
                        unspark()
                    }

                })

        })

        $('#religion').change(function() {

            spark()
            let religion_id = $(this).val()

            $.ajax(

                {
                    url: '{{ route('religion.sect.links') }}',
                    method: "POST",
                    data: {
                        religion_id: religion_id
                    },

                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(res) {

                        $('#sect').html(res)

                        unspark();

                    },
                    error: function(res) {
                        unspark()
                    }

                })



        })


        $(document).ready(function() {

            let form = $('#filter_form')[0];
            let headers = @json($headers);
            spark()

            $('.custom-control-input-og-script').change(function() {
                // console.log($(this).val())
                let elem = $(this)
                let name = $(this).attr('name');

               let val = elem.is(':checked') ? elem.val() : 0;
          
                elem.parent().find('.loader').removeClass('d-none');

                $.ajax({
                    url: '{{ route('students.datatable.update.headers') }}',
                    processData: true, 
                    type: 'POST',
                    data: {
                        header:name,
                        value:val,
                        header_array: headers
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },
                    success: function(res) {
                        elem.parent().find('.loader').addClass('d-none');
                        if (res.state == 'done') {
                            headers = res.headers;

                            console.log(headers)
                            return;

                            let table_headers = '';
                            for (let index = 0; index < headers.length; index++) {
                                table_headers += `<th class="s1" data-field="${headers[index]['name']}">${headers[index]['label']}</th>`;
                            }
                           
                            $('.header_tr').html(table_headers);

                            datatable.draw();

                        }
                    },
                    error: function(res) {
                        elem.parent().find('.loader').addClass('d-none');
                        console.log(res)
                    }
                })

            })


            // my new  script

            $('.custom-control-input').change(function() {
                    let elem = $(this);
                    let name = elem.attr('name');
                    let val = elem.is(':checked') ? elem.val() : 0;

                    elem.parent().find('.loader').removeClass('d-none');

                    $.ajax({
                        url: '{{ route('students.datatable.update.headers') }}',
                        processData: true,
                        type: 'POST',
                        data: {
                            header: name,
                            value: val,
                            header_array: headers
                        },
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        },
                        success: function(res) {
                            elem.parent().find('.loader').addClass('d-none');
                            if (res.state == 'done') {
                                headers = res.headers;

                                console.log(headers);

                                // Clear existing datatable
                                $('#compactTable').DataTable().clear().destroy();

                                // Reinitialize datatable with updated headers
                                initializeDatatable(headers);
                            }
                        },
                        error: function(res) {
                            elem.parent().find('.loader').addClass('d-none');
                            console.log(res);
                        }
                    });
                });

                function initializeDatatable(headers) {

                    // Convert headers object into an array
                    // const headersArray = Object.values(headers);

                    // console.log("length: ", headersArray.length);
                    // return;

                    headers = Object.values(headers);

                    let table_headers = '';
                    for (let index = 0; index < headers.length; index++) {
                        table_headers += `<th class="s1" data-field="${headers[index]['name']}">${headers[index]['label']}</th>`;
                    }

                    $('.header_tr').html(table_headers);

                    // Reinitialize DataTable with updated headers
                    $('#compactTable').DataTable({
                        responsive: true,
                        scrollX: true,
                        processing: true,
                        serverSide: true,
                        pageLength:50,
                        ajax: {
                            url: '{{ route('students.datatable') }}',
                            data: function(d) {
                                d.search = $('#compactTable_filter').find('input').val();
                                d.class_id = $('#class').val();
                                d.stream_id = $('#stream').val();
                                d.club = $('#club').val();
                                d.house_id = $('#house').val();
                                d.religion_id = $('#religion').val();
                                d.religion_sect = $('#sect').val();
                                d.reg_from = $('#reg_from').val();
                                d.reg_to = $('#reg_to').val();
                            },
                        },
                        columns: headers,
                        search: {
                            "regex": true
                        },
                        initComplete: function(settings, json) {
                            unspark();
                        },
                        drawCallback() {
                            unspark();
                            $('#compactTable_filter').keyup(function() {
                                datatable.draw();
                            });
                        }
                    });
                }



             // date range filter
             $('#reg_from, #reg_to').change(function() {
                spark();
                regFromDate = $('#reg_from').val();
                regToDate = $('#reg_to').val();
                datatable.draw();
            });


        let datatable = $('#compactTable').DataTable({

                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                pageLength:50,
                ajax: {

                    url: '{{ route('students.datatable') }}',
                    data: function(d) {
                        d.search = $('#compactTable_filter').find('input').val(),
                            d.class_id = $('#class').val(),
                            d.stream_id = $('#stream').val(),
                            d.club = $('#club').val(),
                            d.house_id = $('#house').val(),
                            d.religion_id = $('#religion').val(),
                            d.religion_sect = $('#sect').val(),
                            // Add registration date range parameters
                            d.reg_from = $('#reg_from').val(),
                            d.reg_to = $('#reg_to').val() 
                    },

                },
                columns: headers,
                search: {
                    "regex": true
                },

                initComplete: function(settings, json) {
                    unspark();
                },
                drawCallback() {
                    unspark();
                    $('#compactTable_filter').keyup(function() {
                        datatable.draw();
                    })
                }

            });

            // delete script
            $('#compactTable').on('click', '.delete', function () {
                var studentUuid = $(this).data('uuid');
                var studentsDeleteUrl = "{{ route('students.destroy.new', ['uuid' => '__UUID__']) }}";
                var myUrl = studentsDeleteUrl.replace('__UUID__',studentUuid);
                

                $.ajax({
                    url: myUrl,
                    type: 'DELETE',
                    dataType: 'json',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function (response) {

                        datatable.ajax.reload();
                        toastr.success(response.msg, response.title);

                        // if (response.state === 'done') {
                            
                        // } else {
                        //     alert('Error: ' + response.message);
                        // }
                    },
                    error: function (xhr, status, error) {
                        // alert('Error: ' + error);
                        toastr.danger(response.msg, response.title);
                    }
                });
            });


            $('body').on('click', '#filter', function(e) {
                spark()
                e.preventDefault();
                datatable.draw();

            })

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

        });

        function generateFile($file_type) {

            let class_id = $('#class').val();
            let stream_id = $('#stream').val();
            let club_id = $('#club').val();
            let religion_id = $('#religion').val();
            let religion_sect = $('#sect').val();
            let house_id = $('#house').val();

            let file_type = $file_type;

            let url = '{{ route('students.ongoing.printouts.pdf') }}';
            url = url + "?file_type=" + file_type + "&class_id=" + class_id + "&stream_id=" + stream_id + "&religion_id=" +
                religion_id + "&sect=" + religion_sect + "&club_id" + club_id + "&house_id" + house_id;
            window.open(url, '_blank');
            console.log(url);

        }
    </script>
@endsection
@endsection
