@extends('layout.index')


@section('top-bread')
    <div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
        <div class="row">
            <div class="ml-3 pd-t-5 pd-b-5">
                <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">GENERATED REPORTS INDRIVE</h1>
            </div>
            <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
                <a class="breadcrumb-item" href="{{ route('dashboard') }}"><i class="icon ion-ios-home-outline"></i>
                    Dashboard</a>
                <a class="breadcrumb-item" href="{{ route('results.reports.generated.reports.index') }}"><i
                        class="icon ion-ios-home-outline"></i> Generated Reports</a>
                <span class="breadcrumb-item active mr-3">Generated Reports Indrive</span>
            </div>
        </div>
    </div>
@endsection

@section('body')

    <style>
        #downloadIcon {
            font-size: 2rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-20px);
            }

            60% {
                transform: translateY(-10px);
            }
        }

        /* Make the first three columns sticky */
        /* end */
    </style>

    <div class="card mt-4">
        <div class="card-body">
            <div class="row clearfix">


                <div class="col-md-12">
                    @include('results.reports.generated.indrive_nav')
                </div>
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 showoff" style="margin-bottom: 1rem">
                                    <span style="text-align:center">
                                        @if (auth()->user()->hasRole('Head Master'))
                                            <h3 id="pb_h3" style="text-align: center; color:indigo !important">
                                                 Approve & Publish Results 
                                            </h3>
                                            <div class="row"
                                                style="padding-right: 2rem; padding-left: 2rem; min-height:20rem; padding-top:1rem">
                                                <div class="container">
                                                    <div style="display: flex; justify-content:space-around">

                                                        <label class="checkbox-label">
                                                            <input type="checkbox"
                                                                {{ $generated_exam_report->include_signature ? 'checked' : '' }}
                                                                name="signature" value="1" class="checkbox-input"
                                                                id="include-signature">
                                                            <span style="padding-left: 1rem"
                                                                class="checkbox-label-text">Include Signature</span>
                                                        </label>

                                                        <button href="javascript:void(0)" title="Publish" type="button"
                                                            style="color:white"
                                                            data-uuid="{{ $generated_exam_report->uuid }}"
                                                            class="escalate_report btn btn-primary btn-sm  {{ $generated_exam_report->is_published ? 'disabled' : '' }}">
                                                            <i class="fa-solid fa-person-arrow-up-from-line"></i> Publish
                                                            Results </button>
                                                    </div>


                                                    <div class="col-md-4">


                                                    </div>
                                                </div>


                                            </div>
                                        @endif
                                    </span>

                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
            {{-- </div> --}}

        </div>
    </div>
    {{-- </div>
    </div>
    </div>
    </div> --}}



@section('scripts')
    <script>
        $(document).ready(function() {
            let uuid = @json($uuid);

            $('#mba').click(function() {
                let url = '{{ route('character.assessments.excel.template', ':id') }}';

                window.open(url.replace(':id', uuid), '_blank');
            })



            /* escalation report */

            $('.escalate_report').click(function() {

                let ct_comment = $('.ct_comment').val();
                let uuid = $(this).data('uuid');
                let signature = $('#include-signature').is(':checked') ? $('#include-signature').val() : 0;

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, make Promotion!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        spark();

                        $.ajax({
                            url: '{{ route('results.reports.escalation.top') }}',
                            method: 'POST',
                            data: {
                                uuid: uuid,
                                ct_comment: ct_comment,
                                signature: signature
                            },

                            beforeSend: function(xhr) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                    'meta[name="csrf-token"]').attr('content'));
                            },

                            success: function(res) {
                                // $('.escalate_report').addClass('disabled');
                                if (res.title == 'success') {
                                    $('.escalate_report').addClass('d-none')
                                    $('.showoff').append(
                                        ` <div>
         <img src="{{ asset('assets/images/conclusion.png') }}" alt="">
         </div>
        <p> Report Already Escalted - Waiting For Academic Approval </p>`
                                    )

                                    Swal.fire({
                                        title: "promoted!",
                                        text: res.msg,
                                        icon: "success"
                                    });
                                }

                                unspark()


                            },

                            error: function(res) {

                                console.log(res)
                                unspark()

                            }
                        })

                    }
                });

            })
        })


        /* END OF AN ATTEMPT */
    </script>
@endsection
@endsection
