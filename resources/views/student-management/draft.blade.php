@extends('layout.index')


@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content">
                <div id="inbox" class="tab-pane fade in animated zoomInDown custom-inbox-message shadow-reset active">
                    <div class="mail-title inbox-bt-mg">
                        <h2>Students</h2>
                        <div class="view-mail-action view-mail-ov-d-n">
                            {{-- <a href="#"><i class="fa fa-reply"></i> Reply</a> --}}
                            <a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i> Print</a>
                            <a type="button" class="btn btn-custon-four btn-primary btn-xs"><i class="fa fa-plus"></i>Add</a>
                        </div>
                    </div>
                    <div class="datatable-dashv1-list custom-datatable-overright">
                        <div id="toolbar1">
                            <select class="form-control">
                                <option value="">Export Basic</option>
                                <option value="all">Export All</option>
                                <option value="selected">Export Selected</option>
                            </select>
                        </div>
                        {{-- <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                        data-total-field="total"
                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                         data-key-events="true" data-show-toggle="true" data-page-size="10" data-resizable="true" data-side-pagination="server" data-cookie="true"
                           data-cookie-id-table="saveId" data-show-export="true" data-url="{{ route('students.datatable') }}" data-click-to-select="true" data-toolbar="#toolbar1"> --}}
                           <table id="table" data-toggle="table" data-page-size="10" data-side-pagination="server" data-url="{{ route('students.datatable') }}" data-total-field="total" data-data-field="rows" data-page-size="10" data-toolbar="#toolbar1" data-pagination="true">
                            <thead>
                                <tr>
                                    @foreach ($headers as $key => $header)
                                    @if ($key == 0)
                                    <th data-field="state" data-checkbox="true"></th>
                                    @endif
                                    <th data-field="{{ $header['name'] }}">{{ $header['label'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            {{-- <tbody>
                                @foreach ($students as $key=>$student )
                                    <tr>
                                        <td></td>
                                        <td>{{++$key}}</td>
                                        <td>{{$student->avatar}}</td>
                                        <td>{{$student->full_name}}</td>
                                        <td>{{$student->gender}}</td>
                                        <td>{{$student->dob}}</td>
                                        <td>{{$student->class_id}}</td>
                                        <td>{{$student->stream_id}}</td>
                                        <td>
                                            <span>
                                                <button type="button" class="btn btn-custon-four btn-info btn-xs"><i class="fa fa-eye"></i></button>
                                                | <button type="button" class="btn btn-custon-four btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                                                | <button data-uuid="{{ $student->uuid }}" type="button" class="btn btn-custon-four btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>
                                            </span></td>
                                    </tr>
                                @endforeach
                            </tbody> --}}
                        </table>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    </div>



    @section('scripts')


    <script>

$(function() {
    $('#table').bootstrapTable({
        url:"{{route('students.datatable')}}"
    })
  })

    $(document).on('click','.delete', function(){

         let uuid = $(this).data('uuid');
         let init_url = '{{ route('students.destroy',[':id']) }}';
         let url = init_url.replace(':id',uuid)

            $.ajax({

                url: url,
                type:'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                success:function(res){
                    console.log(res);
                    let data = res.students;
                    $(function() {
                        $('#table').bootstrapTable('refresh')
                    })
                    // const newArray = data.map((currentValue, index, array) => {
                    //     {sn: ++index, avatar:'',   }
                    //     return ;
                    // });

                    var $table = $('#table');
                    var $button = $('#button')

                    // $table.bootstrapTable('load', data);

                //   let vv =  $table.find('tr[data-uuid="' + uuid + '"]').remove();
                // //    console.log(vv)
                //     $table.bootstrapTable('resetView');


                    // console.log(data);
                    // let table = document.getElementById('table');
                    // let tbody = table.querySelector('tbody')
                    // tbody.innerHTML = '';

                //     data.forEach((student,idx) => {
                //     const row = document.createElement('tr');
                //     row.innerHTML = `
                //     <td></td>
                //     <td>${++idx}</td>
                //     <td>${student.avatar}</td>
                //     <td>${student.full_name}</td>
                //     <td>${student.gender}</td>
                //     <td>${student.dob}</td>
                //     <td>${student.class_id}</td>
                //     <td>${student.stream_id}</td>
                //     <td>
                //         <span>
                //             <button type="button" class="btn btn-custon-four btn-info btn-xs"><i class="fa fa-eye"></i></button>
                //             | <button type="button" class="btn btn-custon-four btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                //             | <button data-uuid="${ student.uuid }" type="button" class="btn btn-custon-four btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>
                //         </span></td>
                //     `;
                //     tbody.appendChild(row);
                // });



                },
                error:function(res){

                    console.log(res)
                }

            })



    })




    </script>

    @endsection

@endsection('body')
<script>



    $('#table').DataTable({
    processing: true,
    serverSide: true,
    ajax:'{{ route('students.datatable') }}',
    columns:[
    {data: 'name', name:'name'},
    {data: 'gender', name:'gender'},
    {data: 'amount_paid', name:'amount_paid'},
    {data: 'balance', name:'balance'},
    {data:'action', name:'action', orderable:false, searchable:false}
    ],
    "columnDefs": [
    // { className: " text-right font-weight-bold", "targets": [ 1 ] },
    // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
    // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
  ],

    });

</script>
