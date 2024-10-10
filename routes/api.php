<?php

use Illuminate\Http\Request;
use App\Http\Controllers\academic\AcademicController;
use App\Http\Controllers\academic\classes\ClassesController;
use App\Http\Controllers\academic\departments\DepartmentsController;
use App\Http\Controllers\academic\elevels\EducationLevelsController;
use App\Http\Controllers\academic\exams\ExamsController;
use App\Http\Controllers\academic\grades\GradesController;
use App\Http\Controllers\academic\semsters\SemestersController;
use App\Http\Controllers\academic\streams\StreamsController;
use App\Http\Controllers\academic\subjects\SubjectsController;
use App\Http\Controllers\academic\years\YearsController;
use App\Http\Controllers\configurations\general\GeneralController;
use App\Http\Controllers\configurations\security\ModulesController;
use App\Http\Controllers\configurations\security\PermissionsController;
use App\Http\Controllers\configurations\security\RolesController;
use App\Http\Controllers\configurations\security\SecurityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GetLinkedRelations;
use App\Http\Controllers\JwtAuthController;
use App\Http\Controllers\results\ReportsController;
use App\Http\Controllers\results\UploadController;
use App\Http\Controllers\students_management\PrintoutsController;
use App\Http\Controllers\students_management\StudentsController;
use App\Http\Controllers\user_management\UsersController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'api', 'middleware' => 'auth:api'  ],function () {

Route::get('', [ DashboardController::class, 'index' ] )->name('dashboard');


Route::group(['prefix' => 'students'], function () {
    // Routes defined here will have the prefix '/admin' and use the 'auth' middleware.
    Route::group(['prefix'=>'on-going'], function(){

        Route::get('', [StudentsController::class, 'index'])->name('students.ongoing');
        Route::post('destroy/{id}', [StudentsController::class, 'destroy'])->name('students.destroy');
        Route::get('datatable', [StudentsController::class, 'datatable'])->name('students.datatable');

        Route::group(['prefix'=>'printouts'], function(){

            Route::get('pdf',[PrintoutsController::class,'pdf'])->name('students.ongoing.printouts.pdf');

        });

        Route::group(['prefix'=>'single'], function(){

            Route::get('registration',[StudentsController::class, 'registerSingle'])->name('students.registration.single');

        });

        Route::group(['prefix'=>'multiple'], function(){

            Route::get('registration',[StudentsController::class, 'registerMultiple'])->name('students.registration.multiple');

        });

    });

    // ... more routes ...
});


/* CONFIGURATIONS */

Route::group(['prefix'=>'configurations'], function(){

Route::get('', [StudentsController::class, 'index'])->name('configurations.index');


/* ACADEMIC MODULE */

Route::group(['prefix'=>'academic'], function(){

    Route::get('', [AcademicController::class, 'index'])->name('academic.index');


    Route::group(['prefix'=>'years'], function(){

        Route::get('', [YearsController::class, 'index'])->name('academic.years.index');
        Route::get('datatables', [YearsController::class, 'datatable'])->name('academic.years.datatable');
        Route::post('store', [YearsController::class, 'store'])->name('academic.years.store');
        Route::post('edit', [YearsController::class, 'edit'])->name('academic.years.edit');
        Route::delete('destroy', [YearsController::class, 'destroy'])->name('academic.years.destroy');

    });


    Route::group(['prefix'=>'classes'], function(){

        Route::get('', [ClassesController::class, 'index'])->name('academic.classes.index');
        Route::get('datatables', [ClassesController::class, 'datatable'])->name('academic.classes.datatable');
        Route::post('store', [ClassesController::class, 'store'])->name('academic.classes.store');
        Route::delete('destroy', [ClassesController::class, 'destroy'])->name('academic.classes.destroy');
        Route::post('edit', [ClassesController::class, 'edit'])->name('academic.classes.edit');


    });


    Route::group(['prefix'=>'subjects'], function(){

        Route::get('', [SubjectsController::class, 'index'])->name('academic.subjects.index');
        Route::post('store', [SubjectsController::class, 'store'])->name('academic.subjects.store');
        Route::delete('destroy', [SubjectsController::class, 'destroy'])->name('academic.subjects.destroy');
        Route::post('edit', [SubjectsController::class, 'edit'])->name('academic.subjects.edit');
        Route::get('datatables', [SubjectsController::class, 'datatable'])->name('academic.subjects.datatable');


    });



    Route::group(['prefix'=>'exams'], function(){

        Route::get('', [ExamsController::class, 'index'])->name('academic.exams.index');
        Route::post('store', [ExamsController::class, 'store'])->name('academic.exams.store');
        Route::delete('destroy', [ExamsController::class, 'destroy'])->name('academic.exams.destroy');
        Route::post('edit', [ExamsController::class, 'edit'])->name('academic.exams.edit');
        Route::get('datatables', [ExamsController::class, 'datatable'])->name('academic.exams.datatable');


    });



    Route::group(['prefix'=>'streams'], function(){

        Route::get('', [StreamsController::class, 'index'])->name('academic.streams.index');
        Route::post('store', [StreamsController::class, 'store'])->name('academic.streams.store');
        Route::delete('destroy', [StreamsController::class, 'destroy'])->name('academic.streams.destroy');
        Route::post('edit', [StreamsController::class, 'edit'])->name('academic.streams.edit');
        Route::get('datatables', [StreamsController::class, 'datatable'])->name('academic.streams.datatable');


    });


    Route::group(['prefix'=>'semesters'], function(){

        Route::get('', [SemestersController::class, 'index'])->name('academic.semesters.index');
        Route::post('store', [SemestersController::class, 'store'])->name('academic.semesters.store');
        Route::delete('destroy', [SemestersController::class, 'destroy'])->name('academic.semesters.destroy');
        Route::post('edit', [SemestersController::class, 'edit'])->name('academic.semesters.edit');
        Route::get('datatables', [SemestersController::class, 'datatable'])->name('academic.semesters.datatable');


    });


    Route::group(['prefix'=>'grades'], function(){

        Route::get('', [GradesController::class, 'index'])->name('academic.grades.index');
        Route::post('store', [GradesController::class, 'store'])->name('academic.grades.store');
        Route::delete('destroy', [GradesController::class, 'destroy'])->name('academic.grades.destroy');
        Route::post('edit', [GradesController::class, 'edit'])->name('academic.grades.edit');
        Route::get('datatables', [GradesController::class, 'datatable'])->name('academic.grades.datatable');


    });


    Route::group(['prefix'=>'education-levels'], function(){

        Route::get('', [EducationLevelsController::class, 'index'])->name('academic.education.levels.index');
        Route::post('store', [EducationLevelsController::class, 'store'])->name('academic.education.levels.store');
        Route::get('datatables', [EducationLevelsController::class, 'datatable'])->name('academic.education.levels.datatable');
        Route::delete('destroy', [EducationLevelsController::class, 'destroy'])->name('academic.education.levels.destroy');
        Route::post('edit', [EducationLevelsController::class, 'edit'])->name('academic.education.levels.edit');


    });



    Route::group(['prefix'=>'departments'], function(){

        Route::get('', [DepartmentsController::class, 'index'])->name('academic.departments.index');
        Route::post('store', [DepartmentsController::class, 'store'])->name('academic.departments.store');
        Route::get('datatables', [DepartmentsController::class, 'datatable'])->name('academic.departments.datatable');
        Route::delete('destroy', [DepartmentsController::class, 'destroy'])->name('academic.departments.destroy');
        Route::post('edit', [DepartmentsController::class, 'edit'])->name('academic.departments.edit');

    });


    });




Route::group(['prefix'=>'security'], function(){

    Route::get('', [SecurityController::class, 'index'])->name('configurations.security.index');

 Route::group(['prefix'=>'roles'], function(){

    Route::get('',[RolesController::class, 'index'])->name('configurations.security.roles');
    Route::get('datatables', [RolesController::class, 'datatable'])->name('configurations.security.roles.datatable');
    Route::post('store', [RolesController::class, 'store'])->name('configurations.security.roles.store');
    Route::post('edit', [RolesController::class, 'edit'])->name('configurations.security.roles.edit');
    Route::delete('destroy', [RolesController::class, 'destroy'])->name('configurations.security.roles.destroy');
    Route::get('assign/{uuid}', [RolesController::class, 'assignPermissionsIndex'])->name('configurations.security.roles.assignment.index');

 });


 Route::group(['prefix'=>'modules'], function(){

    Route::get('',[ModulesController::class, 'index'])->name('configurations.security.modules');
    Route::get('datatables', [ModulesController::class, 'datatable'])->name('configurations.security.modules.datatable');
    Route::post('store', [ModulesController::class, 'store'])->name('configurations.security.modules.store');
    Route::post('edit', [ModulesController::class, 'edit'])->name('configurations.security.modules.edit');
    Route::delete('destroy', [ModulesController::class, 'destroy'])->name('configurations.security.modules.destroy');

 });



 Route::group(['prefix'=>'permissions'], function(){

    Route::get('',[PermissionsController::class, 'index'])->name('configurations.security.roles.permissions');
    Route::get('datatables', [PermissionsController::class, 'datatable'])->name('configurations.security.permissions.datatable');
    Route::post('store', [PermissionsController::class, 'store'])->name('configurations.security.permissions.store');
    Route::post('edit', [PermissionsController::class, 'edit'])->name('configurations.security.permissions.edit');
    Route::delete('destroy', [PermissionsController::class, 'destroy'])->name('configurations.security.permissions.destroy');

 });

});


Route::group(['prefix'=>'general'], function(){

 Route::get('', [GeneralController::class, 'index'])->name('configurations.general.index');



});


});




    /* USER MANAGEMENT MODULE */

Route::group(['prefix'=>'user-management'], function(){

    Route::get('', [UsersController::class, 'index'])->name('user.management.index');

    Route::get('datatables', [UsersController::class, 'datatable'])->name('users.management.datatable');
    Route::post('store', [UsersController::class, 'store'])->name('users.management.store');
    Route::get('registration/{uuid?}', [UsersController::class, 'registration'])->name('users.management.registration');
    Route::post('edit', [UsersController::class, 'edit'])->name('users.management.edit');
    Route::delete('destroy', [UsersController::class, 'destroy'])->name('users.management.destroy');






    // Route::group(['prefix'=>'academic'], function(){

    //     Route::get('', [UsersController::class, 'index'])->name('user.management.index');


    //     // Route::group(['prefix'=>'years'], function(){

    //     //     Route::get('', [YearsController::class, 'index'])->name('academic.years.index');
    //     //     Route::get('datatables', [YearsController::class, 'datatable'])->name('academic.years.datatable');
    //     //     Route::post('store', [YearsController::class, 'store'])->name('academic.years.store');
    //     //     Route::post('edit', [YearsController::class, 'edit'])->name('academic.years.edit');
    //     //     Route::delete('destroy', [YearsController::class, 'destroy'])->name('academic.years.destroy');

    //     // });

    // });



});


/* RESULTS */

Route::group(['prefix'=>'results'], function(){


    Route::group(['prefix'=>'upload'], function(){
        Route::get('excel', [ UploadController::class,'index'])->name('results.template.export.index');




        Route::group(['prefix'=>'system'], function(){

            Route::get('', [ UploadController::class,'system'])->name('results.sytem.entry.index');
            Route::post('query', [UploadController::class, 'templateQuery'])->name('results.sytem.entry.template.query');
            Route::post('store', [UploadController::class, 'store'])->name('results.sytem.entry.store');
            Route::get('registration/{uuid?}', [UploadController::class, 'registration'])->name('users.management.registration');
            Route::post('edit', [UploadController::class, 'edit'])->name('users.management.edit');

        });


    });


    /* REPORTS */

    Route::group(['prefix'=>'reports'], function(){

        Route::get('', [ ReportsController::class,'index'])->name('results.reports.index');
        Route::post('query', [UploadController::class, 'templateQuery'])->name('results.sytem.entry.template.query');
        Route::post('store', [UploadController::class, 'store'])->name('results.sytem.entry.store');
        Route::get('registration/{uuid?}', [UploadController::class, 'registration'])->name('users.management.registration');
        Route::post('edit', [UploadController::class, 'edit'])->name('results.uploads.edit');
        Route::post('load', [ReportsController::class, 'load'])->name('results.reports.load');
        Route::post('datatable', [ReportsController::class, 'datatable'])->name('results.reports.datatable');


        Route::group(['prefix'=>'single-exam'], function(){
            Route::get('{student_id}/{exam_id}/{class_id}/{semester_id}/{academic_year}/{elevel}/{stream_id?}', [ReportsController::class, 'singeExamReportPdf'])->name('reports.single.exam.pdf')->where('stream_id', '.*');



        });


        Route::group(['prefix'=>'single-subject'], function(){
            Route::get('examtype/checked/{academic_year}/{class_id}/{semester}/{subject_id}/{exam_type}/{elevel}/{stream_id?}', [ReportsController::class, 'singleSubjectExamTypeChecked'])->name('results.single.subject.examtype.checked.pdf')->where('stream_id', '.*');;
        });


    });


});




/* GET LINKED RELATIONS */


Route::group(['prefix'=>'links'], function(){


    Route::post('streams',[GetLinkedRelations::class,'streams'])->name('links.streams');
    Route::post('subjects',[GetLinkedRelations::class,'subjects'])->name('links.subjects');
    Route::post('exams',[GetLinkedRelations::class,'exams'])->name('links.exams');
    Route::post('terms',[GetLinkedRelations::class,'terms'])->name('links.terms');


});


});



Route::get('login',[JwtAuthController::class,'index'])->name('sytem.auth.login');
Route::post('login',[JwtAuthController::class,'login'])->name('sytem.auth.login');







require __DIR__.'/auth.php';

