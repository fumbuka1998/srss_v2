
@extends('layout.index')

@section('body')

<style>
    .select2-container {
        min-width: 27rem;
    }
    .chosen-select-single{
        display: flex;
        flex-direction: column;
    }
    .text-color{
       color: #85898c;
    }

    .text-color {
        color: #85898c;
    }
    .permissions-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }
    .permissions-table th,
    .permissions-table td {
        padding: 10px;
        text-align: left;
        vertical-align: middle;
        border: 1px solid #e2e2e2;
    }

    .font{
        font-size: 1.1rem;
    }


    .animated-checkbox {
  position: relative;
  cursor: pointer;
  width: 20px;
  height: 20px;
}

.animated-checkbox input {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
}

.animated-checkbox .checkmark {
  position: absolute;
  top: 0;
  left: 0;
  width: 20px;
  height: 20px;
  border: 1px solid black;
  border-radius: 3px;
  transition: all 0.3s ease-in-out;
}

.animated-checkbox input:checked + .checkmark {
  background-color: black;
}

.animated-checkbox .checkmark::after {
  content: '';
  position: absolute;
  top: 5px;
  left: 5px;
  width: 10px;
  height: 10px;
  border: 1px solid black;
  border-radius: 2px;
  transform: scale(0);
  transition: all 0.3s ease-in-out;
}

.animated-checkbox input:checked + .checkmark::after {
  transform: scale(1);
}

.animated-checkbox:hover .checkmark {
  border-color: #ccc;
}

.animated-checkbox:active .checkmark {
  border-color: #999;
}

.th_now{
    background: #069613;
    color: white;
}

</style>

    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">

            <div class="row">

                <div class="col-md-12">
                    <div class="tab-pane custom-inbox-message">
                    <div class="admintab-wrap mg-b-40">

        <form action="{{ route('configurations.security.roles.assignment.store',$role_uuid) }}" method="POST">
            @csrf
            <table class="permissions-table compact table-responsive">
                <thead>
                    <tr>
                        <th class="th_now">Module</th>
                        <th class="th_now">SubModule</th>
                        <th class="th_now">SubSubModule</th>
                        <th class="th_now">Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $module)


                    @php
                    $moduleRowCount = $module->getDescendantCount() + 1;
                  @endphp

                    <tr>
                        <td style="min-width: 27rem"  rowspan="{{ $moduleRowCount }}"> <h6>{{ $module->name }}</h6></td>

                        @if (!count($module->children))
                            <td></td>
                            <td></td>

                            <td>

                                <div style="display: flex; align-items:center">
                                    @foreach ($module->ModulePermissions as $index => $modulePermission )

                                    @php
                                        $checked = false;
                                        foreach ( $pms_assigned as $pms )
                                            if ($pms->permission_id == $modulePermission->permission_id && $modulePermission->module_id == $pms->module_id  ) {
                                                $checked = true;
                                                break;
                                            }
                                        @endphp


                                    <input   type="checkbox" style="margin-left: 1.5rem;"
                                            data-module-id="{{ $module->id }}"
                                            data-permission-id="{{ $modulePermission->permission_id }}"
                                            value="{{ $modulePermission->permission_id  }}"
                                            name="permission"
                                            class="animated-checkbox  checkuncheck"
                                            {{ $checked ? 'checked' : ''  }}>
                                    <span style="margin-left: 0.5rem;" class="text-color font">{{ $modulePermission->permissions->name }} </span>


                                    @endforeach

                                </div>



                            </td>

                        @endif
                        {{-- <td rowspan="1"></td> --}}
                    </tr>

                    @php
                    $rowspan = 0;
                    $elem = '';
                        foreach ($module->children as $child) {

                            if (!count($child->children)) {
                                $rowspan += 1;

                            }

                        }
                    @endphp

                    @foreach ($module->children as $key=> $child )

                    <tr>
                        <td style="min-width: 27rem" rowspan="{{ count($child->children) +1 }}">{{ $child->name }}</td>

                        <input type="hidden" name="uuid" value="{{ $role_uuid }}" id="uuid">

                        @if (!count($child->children))

                        <td rowspan="1" ></td>

                        <td>
                            <div style="display: flex; align-items:center">
                            @foreach ($child->ModulePermissions as $index => $modulePermission )

                            @php
                                $checked = false;
                                foreach ( $pms_assigned as $pms )
                                    if ($pms->permission_id == $modulePermission->permissions->id && $modulePermission->module_id == $pms->module_id  ) {
                                        $checked = true;
                                        break;
                                    }
                                @endphp


                            <input   type="checkbox" style="margin-left: 1.5rem;"
                                    data-module-id="{{ $child->id }}"
                                    data-permission-id="{{ $modulePermission->permissions->id }}"
                                    value="{{ $modulePermission->permissions->id  }}"
                                    name="permission"
                                    class="animated-checkbox checkuncheck "
                                    {{ $checked ? 'checked' : ''  }}>
                            <span style="margin-left: 0.5rem;" class="text-color font">{{ $modulePermission->permissions->name }} </span>


                            @endforeach

                        </div>
                        </td>

                        @endif




                    </tr>

                    {{-- submodule --}}
                    @if (count($child->children))

                    @foreach ($child->children as $chl )

                    <tr>
                        {{-- <td></td> --}}
                        <td style="min-width: 27rem">  {{ $chl->name }} </td>
                        <td>
                            <div style="display: flex; align-items:center">
                            @foreach ($chl->ModulePermissions as $modulePermission )

                            @php
                                $checked = false;
                                foreach ( $pms_assigned as $pms )
                                    if ($pms->permission_id == $modulePermission->permissions->id && $modulePermission->module_id == $pms->module_id  ) {
                                        $checked = true;
                                        break;
                                    }
                                @endphp

                                    <input type="checkbox" data-module-id="{{ $chl->id }}"
                                    style="margin-left: 1.5rem;"
                                     {{ $checked ? 'checked' : ''  }}
                                     data-permission-id="{{ $modulePermission->permissions->id }}"
                                    value="{{ $modulePermission->permissions->id  }}"
                                    name="permission" class="animated-checkbox checkuncheck"
                                    >&nbsp;
                                    <span class="text-color font">{{ $modulePermission->permissions->name }}</span>
                                    {{-- <input class="checkuncheck"  type="checkbox" value="{{ $modulePermission->permissions->id  }}" name="permissions[]"> <i></i> <span class="text-color font">{{ $modulePermission->permissions->name }}</span>  </label> --}}

                            @endforeach

                        </div>


                        </td>
                    </tr>

                    @endforeach

                    @endif

                    @endforeach
                    @endforeach

                </tbody>
            </table>
        </form>

                    </div>


                    </div>
                </div>

            </div>



        </div>
    </div>



    @section('scripts')

    <script>

$(".checkuncheck").change(function(e) {

    let pmsd = e.target.value;
    let grant = !!false;
    let module_id = $(this).data('module-id')

    spark()

    if($(this).prop('checked')){
        grant = !!true;
    }
    let permissionId = $(this).data('permission-id');
    let uuid = $('#uuid').val();
    let init_url = '{{ route('configurations.security.roles.assignment.store',[':uuid'])}}';
    let url = init_url.replace(':uuid',uuid)

    $.ajax({

        url:url,
        type:'POST',
        data:{
            pmsd : permissionId,
            grant:grant,
            module_id:module_id
        },

        beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },

        success:function(res){
            toast(res.msg,res.title)
            unspark();
            console.log(res)

        },

        error:function(res){
            unspark()
            toast(res.msg,res.title)
            console.log(res)

        }


    })



});
    </script>





    @endsection

    @endsection




