@extends('layout.index')


@section('body')

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<style>

    .mb-3{
        margin-bottom: 2rem;
    }

    .b-radius{

        border-radius: 2rem;
        max-height: 10.9rem;
    }

    .font-icon{
        font-size:42px;
        color: #069613;
    }
    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .b-radius {
        border-radius: 2rem;
        max-height: 10.9rem;
        min-height: 10.9rem;
        cursor: pointer;

    }

    .text_here:hover{
    color: black;
    }
    .text_here{
        color: #21a5ba;
    }

    .s6{

        max-width: 12rem;
        /* /* min-height: 9rem; */
        /* min-height: 10rem; */
    }
    .s5{
        max-height: 7.2rem;
        min-height: 7.2rem;
    }

    </style>

        <div class="row clearfix">
            @if ( $is_teacher || $is_admin)
            <div class="col-md-2 col-sm-12 s6 b-radius">
               <div class="card mb-4 text-light shadow-1 ">
                  <div class="card-body s5 card-img">
                    <a href="{{ route('results.reports.class.report.exam.generate') }}">
                    <div class="text-center b-radius shadow-reset">
                        <i class="material-icons font-icon">create_new_folder</i>
                        <div class="text-center text_here">
                            Create Results Report
                        </div>
                    </div>
                    </a>
                  </div>
               </div>
            </div>

            <div class="col-md-2 col-sm-12 s6 b-radius">
                <div class="card mb-4 text-light shadow-1 ">
                   <div class="card-body s5 card-img">
                    <a href="{{ route('results.reports.generated.reports.index')  }}">
                     <div class="text-center b-radius shadow-reset">
                         <i class="material-icons font-icon">description</i>
                         <div class="text-center text_here">
                            Generated Reports
                         </div>
                     </div>
                    </a>
                   </div>
                </div>
             </div>

             @endif


            @if (auth()->user()->hasRole('Academic') || $is_admin)

            <div class="col-md-2 col-sm-12 s6 b-radius">
                <div class="card mb-4 text-light shadow-1 ">
                   <div class="card-body s5 card-img">
                    <a href="{{ route('results.reports.generated.reports.index')  }}">
                     <div class="text-center b-radius shadow-reset">
                         <i class="material-icons font-icon">escalator</i>
                         <div class="text-center text_here">
                            Escalate to HM
                         </div>
                     </div>
                    </a>
                   </div>
                </div>
             </div>
            @endif


            @if (auth()->user()->hasRole('Head Master'))
            <div class="col-md-2 col-sm-12 s6 b-radius">
                <div class="card mb-4 text-light shadow-1 ">
                   <div class="card-body s5 card-img">
                    <a href="{{ route('results.reports.generated.reports.index')  }}">
                     <div class="text-center b-radius shadow-reset">
                         <i class="material-icons font-icon">publish</i>
                         <div class="text-center text_here">
                            Waiting For Publish
                         </div>
                     </div>
                    </a>
                   </div>
                </div>
             </div>
            @endif


            <div class="col-md-2 col-sm-12 s6 b-radius">
                <div class="card mb-4 text-light shadow-1 ">
                   <div class="card-body s5 card-img">
                    <a href="{{ route('results.reports.loader.index')  }}">
                     <div class="text-center b-radius shadow-reset">
                         <i class="material-icons font-icon">inventory</i>
                         <div class="text-center text_here">
                            Published Results
                         </div>
                     </div>
                    </a>
                   </div>
                </div>
             </div>

        </div>

@endsection
