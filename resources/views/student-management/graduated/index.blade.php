@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mt-3 mb-3 " style="background-color: white;">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5 pl-3">
            <h1 class="pd-0 mg-0 tx-20 text-overflow">STUDENTS GRADUATIONS</h1>
            &nbsp; <small> This Year  <span class="text-danger">  {{ date('Y') }}  </span> </small>

            {{-- &nbsp; <small> From Year  <span class="text-danger">  {{ date('Y') }}  </span> -  <span class="text-success"> {{ date('Y')+1 }} </span>  </small> --}}
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto pr-4">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <span class="breadcrumb-item active">Students Graduation</span>
        </div>
    </div>
</div>
@endsection

@section('body')

<div class="row mt-4">
    <div class="col-md-4 mt-4">
        <div class="card">
            <div class="card-body">
                <p class="text-center" style="color: #238fcf"> <i class="fa-brands fa-apple"></i> <b>GRADUATE CLASS</b></p>
                <div class="col-md-12">
                    <form id="classForm">
                        <div class="form-group">
                            <label for="class">Select Class</label>
                            <select class="form-control" id="class" name="class">
                                @foreach ($graduates as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate</button>

                    </form>
                </div>
            </div>
        </div>

    </div>



    {{-- D-N0NE --}}
    <div class="col-md-8 mt-4 mb-4" id="studentsTableDiv" style="display:none;">
      <div class="card">
        <div class="card-body">

            <div class="row align-items-center mb-4">
                <div class="col text-center">
                    <p class="mb-0" style="color: #36a000;"><i class="fa-brands fa-apple"></i><b>LIST OF GRADUATES</b></p>
                </div>
                <div class="col text-right">
                    <div id="graduateButtonDiv" style="display:none;">
                        <button id="graduateButton" class="btn btn-success">Graduate</button>
                    </div>
                </div>
            </div>



            <div class="datatable-dashv1-list custom-datatable-overright ">
                <table id="studentsTable" class="table table-striped table-bordered table-sm" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Student Image</th>
                            <th>Student Name</th>
                            <th>Gender</th>
                            <th>Student Class</th>
                            <th><input type="checkbox" id="checkAll"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
      </div>
    </div>


</div>




@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let dataTable = null;
        let selectedStudentIds = [];

        $('#classForm').submit(function(event) {
            event.preventDefault();

            let classId = $('#class').val();

            if (dataTable !== null) {
                dataTable.destroy();
            }

            dataTable = $('#studentsTable').DataTable({
                columns: [
                    {data: 'avatar', name:'avatar'},
                    { data: 'name', name: 'name' },
                    { data: 'gender', name:'gender'},
                    { data: 'class', name: 'class' },
                    { data: 'check', name: 'check', orderable:false, searchable:false }
                ],
                drawCallback(){
                    let checkboxes = $(".graduate-checkbox");

                    function toggleGraduateButton() {
                        if (checkboxes.filter(':checked').length > 0) {
                            $('#graduateButtonDiv').show();
                        } else {
                            $('#graduateButtonDiv').hide();
                        }
                    }

                    // Handle "check all" functionality
                    $('#checkAll').click(function(){
                        if ($(this).prop('checked')) {
                            checkboxes.prop('checked', true);
                            selectedStudentIds = checkboxes.map(function() {
                                return $(this).data('student-id');
                            }).get();
                        } else {
                            checkboxes.prop('checked', false);
                            selectedStudentIds = [];
                        }
                        toggleGraduateButton();
                    });

                    // Handle individual checkbox click
                    checkboxes.click(function(){
                        selectedStudentIds = checkboxes.filter(':checked').map(function() {
                            return $(this).data('student-id');
                        }).get();
                        toggleGraduateButton();
                    });

                    // Ensure button visibility is updated when table is redrawn
                    toggleGraduateButton();
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('students.graduates.datatable') }}',
                    type: 'POST',
                    data: { classId: classId, _token: '{{ csrf_token() }}' }
                }
            });

            $('#studentsTableDiv').show();

        });

        $('#graduateButton').click(function() {
            if (selectedStudentIds.length > 0) {
                $.ajax({
                    url: '{{ route('students.graduate') }}',
                    method: 'POST',
                    data: { studentIds: selectedStudentIds, _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                        $('#studentsTable').DataTable().ajax.reload();
                        $('#graduateButtonDiv').hide();
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
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'No Students Selected',
                    text: 'Please select students for graduation.',
                    confirmButtonText: 'OK'
                });
            }
        });

    });
</script>

@endsection
