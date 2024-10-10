@extends('layout.index')


@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content">
                <div id="inbox" class="tab-pane fade in animated zoomInDown custom-inbox-message shadow-reset active">
                    <div class="mail-title inbox-bt-mg">
                        <h2>The Arena *** (Statistics & Comparisons) ***</h2>
                      {{-- <span> <input type="checkbox" id="registration_checkbox" class="checkbox-input float-left" style="width: 17px !important; height: 17px !important; margin-top: 0.1% !important; margin-right: 0.5% !important"> Filters </span> --}}
                        <div class="datatable-btns">
                            {{-- <span class="float-right"> --}}
                                {{-- <a href="javascript:void(0)" title="excel" onclick="generateFile('excel')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                                <a href="javascript:void(0)" title="pdf" onclick="generateFile('pdf')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>&nbsp;Pdf</a>
                                <a href="{{ route('students.registration.single') }}" title="new invoice" id="register" type="button" class=" btn btn-primary btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;New Student</a> --}}
                            {{-- </span> --}}
                        </div>
                    </div>
                    {{-- <div class="datatable-dashv1-list custom-datatable-overright">
                           <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                            <thead>
                                <tr>
                                    @foreach ($headers as $key => $header)
                                    <th data-field="{{ $header['name'] }}">{{ $header['label'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                        </table>

                    </div> --}}
                </div>
            </div>
        </div>
        </div>



    </div>
    </div>


    @section('scripts')


    <script>





    </script>
@endsection
@endsection







