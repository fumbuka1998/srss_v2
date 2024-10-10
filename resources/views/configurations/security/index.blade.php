@extends('layout.index')


@section('body')

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


<style>

.s6{
    max-width: 12rem;
}
.s5{
    max-height: 7.2rem;
    min-height: 7.2rem;
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

</style>

<div class="row">
    <div class="col-md-2 col-sm-12 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('configurations.security.roles')  }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">security</i>
                 <div class="text-center text_here">
                    Roles & Permissions
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>
</div>

@endsection
