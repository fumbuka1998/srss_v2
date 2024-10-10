@extends('layout.index')


@section('body')
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<style>

.mb-3{
    margin-bottom: 2rem;
}

.font-icon{
    font-size:42px;
    color: #069613;
}
.card {
    transition: transform 0.2s ease-in-out;
}

.ellipsis {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
}
.s5{
    max-height: 7.2rem;
    min-height: 7.2rem;
}

</style>



<div class="row clearfix">
    <div class="col-md-2 col-sm-12 s6 b-radius">
       <div class="card mb-4 text-light shadow-1 ">
          <div class="card-body s5 card-img">
            <a href="{{ route('academic.years.index') }}">
            <div class="text-center b-radius shadow-reset">
                <i class="material-icons font-icon">event</i>
                <div class="text-center text_here">
                    Academic Years
                </div>
            </div>
            </a>
          </div>
       </div>
    </div>

    <div class="col-md-2 col-sm-12 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.semesters.index')  }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">date_range</i>
                 <div class="text-center text_here">
                    Semesters
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>


     <div class="col-md-2 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{route('academic.classes.index')}}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">event</i>
                 <div class="text-center text_here">
                    Classes
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>

     <div class="col-md-2 col-sm-12 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.streams.index') }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">assignment</i>
                 <div class="text-center text_here">
                     Streams
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>


     <div class="col-md-2 col-sm-12 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.education.levels.index')  }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">trending_up</i>
                 <div class="text-center text_here">
                     Education Levels
                 </div>
             </div>
            </a>

           </div>
        </div>
     </div>


     <div class="col-md-2 col-sm-12 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.exams.index')  }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">description</i>
                 <div class="text-center text_here">
                    Exams
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>

     <div class="col-md-2 col-sm-12 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.subjects.index')  }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">menu_book</i>
                 <div class="text-center text_here">
                    Subjects
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>

     <div class="col-md-2 col-sm-12 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.departments.index') }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">business</i>
                 <div class="text-center text_here">
                    Departments
                 </div>
             </div>
            </a>

           </div>
        </div>
     </div>

     <div class="col-md-2 col-sm-12 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.grades.groups') }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">class</i>
                 <div class="text-center text_here">
                    Grade Groups
                 </div>
             </div>
           </div>
        </div>
     </div>


     <div class="col-md-2 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.exam.reports.index') }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">folder</i>
                 <div class="text-center text_here ellipsis">
                    Exam Reports
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>

     <div class="col-md-2 s6 b-radius">
        <div class="card mb-4 text-light shadow-1">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.class.teachers.index') }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">people</i>
                 <div class="text-center text_here ellipsis">
                    CT assignment
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>



     <div class="col-md-2 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 ">
           <div class="card-body s5 card-img">
            <a href="{{ route('academic.subjects.assignment.index') }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">person</i>
                 <div class="text-center text_here ellipsis">
                    ST assignment
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>



     <div class="col-md-2 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 " title="Class Stream Subjects Allocation">
           <div class="card-body s5 card-img">
            <a href="{{ route('streams.subjects.assignment.general.index') }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">library_books</i>
                 <div class="text-center text_here ellipsis">
                    Class Stream Subjects Allocation
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>


     <div class="col-md-2 s6 b-radius">
        <div class="card mb-4 text-light shadow-1" title="Students Subjects Allocation">
           <div class="card-body s5 card-img">
            <a href="{{ route('students.subjects.assignment.general.index') }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">assignment_add</i>
                 <div class="text-center text_here ellipsis">
                    Students Subjects Allocation
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>


     <div class="col-md-2 s6 b-radius">
        <div class="card mb-4 text-light shadow-1 " title="Character Assessment Setup">
           <div class="card-body s5 card-img">
            <a href="{{ route('character.assessment.index') }}">
             <div class="text-center b-radius shadow-reset">
                 <i class="material-icons font-icon">checklist</i>
                 <div class="text-center text_here ellipsis">
                    Character Assessment Setup
                 </div>
             </div>
            </a>
           </div>
        </div>
     </div>

     {{-- adding comments --}}

     <div class="col-md-2 s6 b-radius">
      <div class="card mb-4 text-light shadow-1 " title="Comments Configurations">
         <div class="card-body s5 card-img">
          <a href="{{ route('comments.configure.index') }}">
           <div class="text-center b-radius shadow-reset">
               <i class="material-icons font-icon">dehaze</i>
               <div class="text-center text_here ellipsis">
                   Comments Configure
               </div>
           </div>
          </a>
         </div>
      </div>
   </div>

 </div>
{{-- ENDDDDDDDD --}}


@endsection
