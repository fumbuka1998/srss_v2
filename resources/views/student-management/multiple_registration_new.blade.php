@extends('layout.index')


@section('body')



<style>



</style>

<div class="card">
    <div class="card-header"></div>
    <div class="card-body">
        <div class="row mb-3 ml-2">
            <div class="col-md-4 mg-t-20 mg-lg-t-0">
                <div class="custom-control custom-radio">
                   <input type="radio" {{ $activeRadio == 'single' ? 'checked' : '' }} value="single" name="a" class="custom-control-input" id="radio2">
                   <label class="custom-control-label" for="radio2">Single Registration</label>
                </div>
             </div>
             <div class="col-md-4 mg-t-20 mg-lg-t-0">
                <div class="custom-control custom-radio">
                   <input type="radio" value="multiple" {{ $activeRadio == 'multiple' ? 'checked' : '' }} name="a" class="custom-control-input" id="radio3">
                   <label class="custom-control-label"  for="radio3">Multiple Registration</label>
                </div>
             </div>

        </div>

        @include('student-management.multiple_reg_nav')

        <div class="row mt-4">
            <div class="col-md-12">

                <div class="clearfix">
                    <p class="text-bold"><i class="fa fa-paperclip"></i> Download Excel Template  <span>(1+) - </span>
                    </p>
                    <ul class="mg-y-20-force mail-attach-list">
                       <li>
                          <a onclick="downloadTemplate()" href="javascript:void(0)" class="thumbnail">
                             <div class="mail-file-icon">
                                <i class="fa fa-file-excel"></i>
                             </div>
                             <div class="caption">
                                <p class="text-main mg-0"> Students-template.xlsx</p>
                                <small class="text-muted"> {{ date('Y-m-d')  }} </small>
                             </div>
                          </a>
                       </li>
                    </ul>
                 </div>
            </div>
        </div>



    </div>
</div>

        </div>
    </div>
</div>


@section('scripts')

<script>


  $('input[type="radio"]').change(function(event) {
    var selectedValue = $(this).val();
    if (selectedValue === 'single') {
        window.location.href = '{{ route('students.registration.single')  }}';
    } else if (selectedValue === 'multiple') {

        window.location.href = '{{ route('students.registration.multiple')  }}';
    }
});


function downloadTemplate(){

  let  url = '{{ route('students.registration.export') }}';
  window.open(url,'_blank');

}


</script>

@endsection





@endsection
