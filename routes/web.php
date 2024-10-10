<?php

use App\Http\Controllers\academic\AcademicController;
use App\Http\Controllers\academic\ca\CharacterAssessmentsAllocationController;
use App\Http\Controllers\academic\ca\CharacterAssessmentsController as CaCharacterAssessmentsController;
use App\Http\Controllers\academic\classes\ClassesController;
use App\Http\Controllers\academic\departments\DepartmentsController;
use App\Http\Controllers\academic\elevels\EducationLevelsController;
use App\Http\Controllers\academic\exams\ExamsController;
use App\Http\Controllers\academic\grades\GradesController;
use App\Http\Controllers\academic\semsters\SemestersController;
use App\Http\Controllers\academic\streams\ClassAssingmentStreamSubjects;
use App\Http\Controllers\academic\streams\StreamsController;
use App\Http\Controllers\academic\StreamSubjectsAssignmentController;
use App\Http\Controllers\academic\subjects\SubjectsController;
use App\Http\Controllers\academic\years\YearsController;
use App\Http\Controllers\arena\ArenaController;
use App\Http\Controllers\configurations\assign_classes\ClassesAssignmentController;
use App\Http\Controllers\configurations\assign_subjects\SubjectsAssignmentController;
use App\Http\Controllers\configurations\character_assessments\CharacterAssessmentsController;
use App\Http\Controllers\configurations\clubs\ClubsController;
use App\Http\Controllers\configurations\general\GeneralController;
use App\Http\Controllers\configurations\houses\HousesController;
use App\Http\Controllers\configurations\religion\ReligionController;
use App\Http\Controllers\configurations\security\ModulesController;
use App\Http\Controllers\configurations\security\PermissionsController;
use App\Http\Controllers\configurations\security\RolesController;
use App\Http\Controllers\configurations\security\SecurityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\configurations\Comments\Comment_configurations;
use App\Http\Controllers\GetLinkedRelations;
use App\Http\Controllers\ExamReportsController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\results\ReportsController;
use App\Http\Controllers\results\ResultsController;
use App\Http\Controllers\results\ResultsUploadExcelController;
use App\Http\Controllers\results\UploadController;
use App\Http\Controllers\ResultsLoaderController;
use App\Http\Controllers\students_management\AssignStudentSubjects;
use App\Http\Controllers\students_management\GraduationController;
use App\Http\Controllers\students_management\PrintoutsController;
use App\Http\Controllers\students_management\PromotionController;
use App\Http\Controllers\students_management\StudentsController;
use App\Http\Controllers\user_management\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix'=>'/', 'middleware' => 'auth'  ],function () {

Route::get('', [ DashboardController::class, 'index' ] )->name('dashboard');


Route::group(['prefix' => 'students'], function () {
    // Routes defined here will have the prefix '/admin' and use the 'auth' middleware.

    Route::group(['prefix'=>'profile/{uuid}'], function(){

        Route::get('', [StudentsController::class, 'profile'])->name('students.profile');
        Route::post('edit-image', [StudentsController::class, 'updateProfilePic'])->name('students.image.update');
        // route for profile edit
        Route::get('edit-req', [StudentsController::class, 'editStudentProfile'])->name('students.management.registration.edit.req');
        Route::post('update-basic', [StudentsController::class, 'updateStudentBasic'])->name('students.management.profile.basic.update');




        Route::group(['prefix'=>'subjects/assignment'], function(){

            Route::get('', [AssignStudentSubjects::class, 'index'])->name('students.profile.subjects.allocated.index');
            Route::get('datatables', [AssignStudentSubjects::class, 'datatable'])->name('students.subjects.assignment.datatable');
            Route::post('store', [AssignStudentSubjects::class, 'store'])->name('students.subjects.assignment.store');
            Route::post('edit', [AssignStudentSubjects::class, 'edit'])->name('students.subjects.assignment.edit');
            Route::delete('destroy', [AssignStudentSubjects::class, 'destroy'])->name('students.subjects.assignment.destroy');



        });

        Route::group(['prefix'=>'guardians'], function(){

            Route::get('', [StudentsController::class, 'contactPeopleIndex'])->name('students.profile.contact.people.index');
            Route::get('datatable', [StudentsController::class, 'contactPeopleDatatable'])->name('students.profile.contact.people.datatable');
            Route::post('students-contact-people-save',[StudentsController::class,'contactPeopleStore'])->name('student.profile.contact.people.store');
            Route::post('students-contact-people-edit/{id}',[StudentsController::class,'contactPersonEdit'])->name('student.profile.contact.people.edit');



            /* FROM PAYFEE */

            // Route::delete('students-contact-people-delete/{id}',[StudentsProfileController::class,'contactPeopleDestroy'])->name('student.profile.contact.people.destroy');
            // Route::get('students-contact-people/{id}',[StudentsProfileController::class,'contactPeople'])->name('student.profile.contact.people');

            // Route::post('students-contact-people-edit/{id}',[StudentsProfileController::class,'editContactPeople'])->name('student.profile.contact.people.edit');


        });


        Route::group(['prefix'=>'results'], function(){

            Route::get('', [StudentsController::class, 'studentResultsIndex'])->name('students.results.preview.index');
            Route::get('datatable', [StudentsController::class, 'studentResultsDatatable'])->name('students.profile.results.datatable');
            Route::get('/results/student_single_report_indrive/{report_uuid}', [StudentsController::class, 'studentSingleReportIndrive'])->name('single.student.results.reports.generate.report.indrive');
            Route::get('/results/student_single_report_print/{report_uuid}/{generated_exam_report_uuid}', [StudentsController::class, 'studentSingleReportPrint'])->name('single.student.results.reports.generate.report.print');


            // Route::get('datatables', [AssignStudentSubjects::class, 'datatable'])->name('students.subjects.assignment.datatable');
            // Route::post('store', [AssignStudentSubjects::class, 'store'])->name('students.results.preview.assignment.store');
            // Route::post('edit', [AssignStudentSubjects::class, 'edit'])->name('students.results.preview.assignment.edit');
            // Route::delete('destroy', [AssignStudentSubjects::class, 'destroy'])->name('students.results.preview.assignment.destroy');

        });


    });





    Route::group(['prefix'=>'on-going'], function(){

        Route::get('', [StudentsController::class, 'index'])->name('students.ongoing');
        Route::post('destroy/{id}', [StudentsController::class, 'destroy'])->name('students.destroy');
        Route::delete('/students/{uuid}', [StudentsController::class, 'destroyStudent'])->name('students.destroy.new');
        Route::get('datatable', [StudentsController::class, 'datatable'])->name('students.datatable');
        Route::post('headers-update', [StudentsController::class, 'updateDtHeaders'])->name('students.datatable.update.headers');



        /* assign student subjects */




        /* end subjects assignments */


        /* WIZARD REGISTRATION */

        Route::group(['prefix' => 'registration'], function () {



        Route::post('duplicate-check', [StudentsController::class, 'checkForAdmissionNumberDuplicacy'])->name('students.admission.duplicate.check');



        Route::post('step1', [StudentsController::class, 'step1Store'])->name('students.step.1.store');
        Route::post('step2', [StudentsController::class, 'step2Store'])->name('students.step.2.store');
        Route::post('step3', [StudentsController::class, 'step3Store'])->name('students.step.3.store');
        Route::post('step4', [StudentsController::class, 'step1Store'])->name('students.step.4.store');


        Route::post('store', [StudentsController::class, 'allWizardStepsInOneStore'])->name('students.all.steps.store');





        });

        Route::group(['prefix'=>'printouts'], function(){

            Route::get('pdf',[PrintoutsController::class,'pdf'])->name('students.ongoing.printouts.pdf');

        });

        Route::group(['prefix'=>'single'], function(){

            Route::get('registration',[StudentsController::class, 'registerSingle'])->name('students.registration.single');
            Route::get('{uuid}', [StudentsController::class, 'editStudent'])->name('students.edit');
            Route::post('/students/updatestep1/', [StudentsController::class, 'updateStep1Store'])->name('students.step1.update');
            Route::post('/students/updatestep2/', [StudentsController::class, 'updateStep2Store'])->name('students.step2.update');
            Route::post('/students/updatestep3/', [StudentsController::class, 'updateStep3Store'])->name('students.step3.update');

        });

        Route::group(['prefix'=>'multiple'], function(){

            Route::get('registration',[StudentsController::class, 'registerMultiple'])->name('students.registration.multiple');
            Route::get('excel-upload',[StudentsController::class, 'registerMultiplePreview'])->name('students.registration.multiple.preview');
            Route::post('pre-excel-import',[StudentsController::class, 'preImportStudents'])->name('students.registration.multiple.pre.excel.import');
            Route::get('/student/export/template', [StudentsController::class, 'studentsExcelTemplateExport'])->name('students.registration.export');
            Route::post('/student/import/excel', [StudentsController::class, 'importtemplate'])->name('students.registration.import');
            Route::get('excel-errors', [StudentsController::class, 'downloadExcelValidationErrors'])->name('students.upload.excel.import.errors');

        });

    });
    // ... more routes ...
         //graduated routes
         Route::group(['prefix' => 'graduated'], function () {
            route::get('', [GraduationController::class, 'index'])->name('students.graduated');
            Route::post('datatable', [GraduationController::class, 'datatable'])->name('students.graduates.datatable');
            Route::post('/graduate', [GraduationController::class,'graduate'])->name('students.graduate');

            route::get('/allumni-management', [GraduationController::class, 'allumniIndex'])->name('students.allumni');
            Route::get('/allumni-datatables', [GraduationController::class, 'allumni_datatable'])->name('allumni.management.datatable');

        });

        //promotion routes
        Route::group(['prefix' => 'promotion'], function () {
            route::get('', [PromotionController::class, 'index'])->name('students.promotion');
            route::post('datatable', [PromotionController::class,'datatable'])->name('students.promotion.datatable');
            Route::post('/promote', [PromotionController::class,'promote'])->name('students.promote');
            Route::post('/getStreams', [PromotionController::class, 'getStreams'])->name('get.streams');

             /* class filters to streams */

            Route::get('to_class/edit', [PromotionController::class,'fromClassFilter'])->name('students.to_class.filter');
            Route::get('from_class/edit', [PromotionController::class,'toClassFilter'])->name('students.from_class.filter');

            //manage promotion
            Route::get('manage', [PromotionController::class, 'managePromotion'])->name('students.manage');
            Route::get('manage/datatable', [PromotionController::class, 'promotionDatatable'])->name('manage.promotion.datatable');
            Route::put('/reset-promotion/{id}',[PromotionController::class, 'resetStudentPromotion'])->name('reset.student.promotion');

        });


});


/* THE ARENA */

Route::group(['prefix'=>'arena'], function(){

    // Route::get('', [ ArenaController::class, 'index' ] )->name('acs.arena.index');
    Route::group(['prefix'=>'subjects'], function(){
        Route::get('', [ ArenaController::class, 'subjects' ] )->name('acs.arena.subjects.index');
    });



});



/* END ARENA */



/* THE ACADEMIC MODULE */

Route::group(['prefix'=> 'academic'], function(){

    Route::middleware('check.module.access:33')->group(function () {

        Route::group(['prefix'=>'schedule'], function(){

            Route::get('', [ExamScheduleController::class,'index'])->name('exams.schedule.index');
            Route::get('datatables', [ExamScheduleController::class, 'datatable'])->name('exams.schedule.index.datatable');
            Route::post('store', [ExamScheduleController::class, 'store'])->name('exams.schedule.index.store');
            Route::post('edit', [ExamScheduleController::class, 'edit'])->name('exams.schedule.index.edit');
            Route::delete('destroy', [ExamScheduleController::class, 'destroy'])->name('exams.schedule.index.destroy');

        });

        Route::group(['prefix'=>'waiting-for-marking'], function(){

            Route::get('', [ExamsController::class,'waitingForMarkingIndex'])->name('exams.waiting.marking.index');
            Route::get('datatable', [ExamsController::class, 'waitingForMarkingDatatable'])->name('exams.waiting.marking.datatable');

            Route::get('countdown', [ExamsController::class, 'getCountdownTime'])->name('exams.waiting.marking.countdown');

            Route::post('/extend-marking-time', [ExamsController::class, 'extendMarkingTime'])->name('extend.marking.time');


            });


            Route::group(['prefix'=>'upcoming'], function(){

                Route::get('', [ExamsController::class,'upComingExamsIndex'])->name('exams.upcoming.marking.index');
                Route::get('datatable', [ExamsController::class, 'upComingDatatable'])->name('exams.upcoming.marking.datatable');

                // Route::post('store', [ExamsController::class, 'store'])->name('exams.schedule.index.store');
                // Route::post('edit', [ExamsController::class, 'edit'])->name('exams.schedule.index.edit');

                });

            Route::group(['prefix'=>'complete'], function(){

            Route::get('', [ResultsController::class, 'completeMarkingIndex'])->name('results.sytem.complete.entry.index');
            Route::post('complete-store', [ResultsController::class, 'completestore'])->name('results.sytem.complete.entry.store');
            Route::get('complete-datatables', [ResultsController::class, 'completedatatable'])->name('results.sytem.complete.entry.datatable');
            Route::post('complete-edit', [ResultsController::class, 'completeEdit'])->name('results.sytem.complete.entry.edit');

            // print students results list for preview
            // Route::get('/print-preview',[ResultsController::class, 'printPreview'])->name('results.complete.print.preview');
            Route::get('print-preview/{year_id}/{semester_id}/{class_id}/{stream_id}/{exam_id}/{subject_id}', [ResultsController::class, 'printPreview'])->name('results.complete.print.preview');



            Route::get('complete-marking-indrive/{year_id}/{semester_id}/{class_id}/{stream_id}/{exam_id}/{subject_id}', [ResultsController::class, 'completedMarkingInDrive'])->name('results.sytem.complete.marking.indrive');
            Route::post('complete-marking-indrive-datatable', [ResultsController::class, 'completedMarkingInDriveDatatable'])->name('results.sytem.complete.marking.indrive.datatable');

            });

            Route::group(['prefix'=>'incomplete'], function(){

            Route::get('', [ResultsController::class, 'IncompleteMarkingIndex'])->name('results.sytem.incomplete.entry.index');
            Route::post('incomplete-store', [ResultsController::class, 'Incompletestore'])->name('results.sytem.incomplete.entry.store');
            Route::get('incomplete-datatables', [ResultsController::class, 'incompletedResultsDatatable'])->name('results.sytem.results.upload.incomplete.datatable');
            Route::post('incomplete-edit', [ResultsController::class, 'IncompleteEdit'])->name('results.sytem.incomplete.entry.edit');

            });




        Route::group(['prefix'=>'system'], function(){

            Route::get('{uuid}/{specific_uuid}/{grade_group_id?}/{sbjct_id?}/{class_id?}/{stream_id?}', [ UploadController::class,'system'])->name('results.sytem.entry.index');

            Route::post('query', [UploadController::class, 'templateQuery'])->name('results.sytem.entry.template.query');
            Route::post('store', [UploadController::class, 'store'])->name('results.sytem.entry.store');

            // Route::get('registration/{uuid?}', [UploadController::class, 'registration'])->name('users.management.registration');
            // Route::post('edit', [UploadController::class, 'edit'])->name('users.management.edit');


            Route::group(['prefix'=>'drafts'], function(){

            Route::get('', [ResultsController::class, 'DraftsIndex'])->name('results.sytem.drafts.entry.index');
            Route::post('draftstore', [UploadController::class, 'Draftstore'])->name('results.sytem.drafts.entry.store');
            Route::get('draftdatatables', [UploadController::class, 'Draftdatatable'])->name('results.sytem.drafts.entry.datatable');
            Route::get('editable/{year_id}/{semester_id}/{class_id}/{stream_id}/{exam_id}/{subject_id}', [ResultsController::class, 'draftEditable'])->name('results.sytem.drafts.editable.index');
            Route::post('editable-datatable', [ResultsController::class, 'draftsEditableDatatable'])->name('results.sytem.drafts.editable.datatable');
            Route::post('editable-query', [ResultsController::class, 'draftsEditableEdit'])->name('results.sytem.drafts.editable.edit');

            });




        });


    });

});


/* END ACADEMIC MODULE */




/* CONFIGURATIONS */

Route::group(['prefix'=>'configurations'], function(){

// Route::get('', [StudentsController::class, 'index'])->name('configurations.index');

/* RELIGION SETUP */

Route::middleware('check.module.access:8')->group(function () {

    Route::group(['prefix'=>'character-assessment'], function(){

        Route::get('', [CharacterAssessmentsController::class, 'index'])->name('character.assessment.index');
        Route::get('datatables', [CharacterAssessmentsController::class, 'datatable'])->name('character.assessment.datatable');
        Route::post('store', [CharacterAssessmentsController::class, 'store'])->name('character.assessment.store');
        Route::post('edit', [CharacterAssessmentsController::class, 'edit'])->name('character.assessment.edit');
        Route::delete('destroy', [CharacterAssessmentsController::class, 'destroy'])->name('character.assessment.destroy');

    });

    // comments

    Route::group(['prefix'=>'comments-configuration'], function(){

        Route::get('', [Comment_configurations::class, 'index'])->name('comments.configure.index');
        Route::get('datatables', [Comment_configurations::class, 'datatable'])->name('comments.configure.datatable');
        Route::post('store', [Comment_configurations::class, 'store'])->name('comments.configure.store');
        Route::post('edit', [Comment_configurations::class, 'edit'])->name('comments.configure.edit');
        Route::delete('destroy', [Comment_configurations::class, 'destroy'])->name('comments.configure.destroy');

    });




    Route::group(['prefix'=>'religion'], function(){

        Route::get('', [ReligionController::class, 'index'])->name('religion.index');
        Route::get('datatables', [ReligionController::class, 'datatable'])->name('religion.datatable');
        Route::post('store', [ReligionController::class, 'store'])->name('religion.store');
        Route::post('edit', [ReligionController::class, 'edit'])->name('religion.edit');
        Route::delete('destroy', [ReligionController::class, 'destroy'])->name('religion.destroy');


        Route::get('sect-datatables', [ReligionController::class, 'sectDatatable'])->name('religion.sect.datatable');
        Route::post('sect-store', [ReligionController::class, 'sectStore'])->name('religion.sect.store');
        Route::post('sect-edit', [ReligionController::class, 'sectEdit'])->name('religion.sect.edit');
        Route::delete('sect-destroy', [ReligionController::class, 'sectDestroy'])->name('religion.sect.destroy');

    });



    /* HOUSES */

    Route::group(['prefix'=>'houses'], function(){

        Route::get('', [HousesController::class, 'index'])->name('houses.index');
        Route::get('datatables', [HousesController::class, 'datatable'])->name('houses.datatable');
        Route::post('store', [HousesController::class, 'store'])->name('houses.store');
        Route::post('edit', [HousesController::class, 'edit'])->name('houses.edit');
        Route::delete('destroy', [HousesController::class, 'destroy'])->name('houses.destroy');

    });


    /* CLUBS */

    Route::group(['prefix'=>'clubs'], function(){

        Route::get('', [ClubsController::class, 'index'])->name('clubs.index');
        Route::get('datatables', [ClubsController::class, 'datatable'])->name('clubs.datatable');
        Route::post('store', [ClubsController::class, 'store'])->name('clubs.store');
        Route::post('edit', [ClubsController::class, 'edit'])->name('clubs.edit');
        Route::delete('destroy', [ClubsController::class, 'destroy'])->name('clubs.destroy');

    });


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

        Route::get('fetch-preliminaries', [ExamsController::class, 'assignPreliminaries'])->name('academic.exams.preliminaries.fetch');

});



    Route::group(['prefix'=>'streams'], function(){

        Route::get('', [StreamsController::class, 'index'])->name('academic.streams.index');
        Route::post('store', [StreamsController::class, 'store'])->name('academic.streams.store');

        Route::delete('destroy', [StreamsController::class, 'destroy'])->name('academic.streams.destroy');
        Route::post('edit', [StreamsController::class, 'edit'])->name('academic.streams.edit');
        Route::get('datatables', [StreamsController::class, 'datatable'])->name('academic.streams.datatable');


        // Route::group(['prefix'=>'assign'], function(){

        // Route::get('subjects', [ClassAssingmentStreamSubjects::class, 'assignSubjects'])->name('academic.streams.assign.subjects.index');
        // Route::get('subjects/datatables', [ClassAssingmentStreamSubjects::class, 'assignedSubjectsDatatable'])->name('academic.streams.assigned.subjects.datatable');

        // Route::get('subjects', [ClassAssingmentStreamSubjects::class, 'assignSubjects'])->name('academic.streams.assign.subjects.index');
        // Route::get('subjects', [ClassAssingmentStreamSubjects::class, 'assignSubjects'])->name('academic.streams.assign.subjects.index');


        });


    });


    Route::group(['prefix'=>'semesters'], function(){

        Route::get('', [SemestersController::class, 'index'])->name('academic.semesters.index');
        Route::post('store', [SemestersController::class, 'store'])->name('academic.semesters.store');
        Route::delete('destroy', [SemestersController::class, 'destroy'])->name('academic.semesters.destroy');
        Route::post('edit', [SemestersController::class, 'edit'])->name('academic.semesters.edit');
        Route::get('datatables', [SemestersController::class, 'datatable'])->name('academic.semesters.datatable');


    });







        /* GRADE GROUPS */

        Route::group(['prefix' => 'grade-groups'],   function(){

            Route::get('', [GradesController::class, 'gradeGroups'])->name('academic.grades.groups');
            Route::post('store', [GradesController::class, 'groupStore'])->name('academic.grades.groups.store');
            Route::delete('destroy', [GradesController::class, 'groupDestroy'])->name('academic.grades.groups.destroy');
            Route::post('edit', [GradesController::class, 'groupEdit'])->name('academic.grades.groups.edit');
            Route::get('datatables', [GradesController::class, 'groupDatatable'])->name('academic.grades.groups.datatable');


            Route::group(['prefix'=>'grades'], function(){
                Route::get('{uuid}', [GradesController::class, 'index'])->name('academic.grades.index');
                Route::post('store', [GradesController::class, 'store'])->name('academic.grades.store');
                Route::delete('destroy', [GradesController::class, 'destroy'])->name('academic.grades.destroy');
                Route::post('edit', [GradesController::class, 'edit'])->name('academic.grades.edit');
                Route::post('datatables', [GradesController::class, 'datatable'])->name('academic.grades.datatable');
        });



    });


    Route::group(['prefix'=>'education-levels'], function(){

        Route::get('', [EducationLevelsController::class, 'index'])->name('academic.education.levels.index');
        Route::post('store', [EducationLevelsController::class, 'store'])->name('academic.education.levels.store');
        Route::get('datatables', [EducationLevelsController::class, 'datatable'])->name('academic.education.levels.datatable');
        Route::delete('destroy', [EducationLevelsController::class, 'destroy'])->name('academic.education.levels.destroy');
        Route::post('edit', [EducationLevelsController::class, 'edit'])->name('academic.education.levels.edit');


    });


    Route::group(['prefix'=>'exam-reports'], function(){

        Route::get('', [ExamReportsController::class, 'index'])->name('academic.exam.reports.index');
        Route::post('store', [ExamReportsController::class, 'store'])->name('academic.exam.reports.store');
        Route::get('datatables', [ExamReportsController::class, 'datatable'])->name('academic.exam.reports.datatable');
        Route::delete('destroy', [ExamReportsController::class, 'destroy'])->name('academic.exam.reports.destroy');
        Route::post('edit', [ExamReportsController::class, 'edit'])->name('academic.exam.reports.edit');


    });



    Route::group(['prefix'=>'departments'], function(){

        Route::get('', [DepartmentsController::class, 'index'])->name('academic.departments.index');
        Route::post('store', [DepartmentsController::class, 'store'])->name('academic.departments.store');
        Route::get('datatables', [DepartmentsController::class, 'datatable'])->name('academic.departments.datatable');
        Route::delete('destroy', [DepartmentsController::class, 'destroy'])->name('academic.departments.destroy');
        Route::post('edit', [DepartmentsController::class, 'edit'])->name('academic.departments.edit');

    });




    Route::group(['prefix'=>'classes-assignment'], function(){

        Route::get('', [ClassesAssignmentController::class, 'index'])->name('academic.class.teachers.index');
        Route::post('store', [ClassesAssignmentController::class, 'store'])->name('academic.class.teachers.store');
        Route::get('datatables', [ClassesAssignmentController::class, 'datatable'])->name('academic.class.teachers.datatable');
        Route::delete('destroy', [ClassesAssignmentController::class, 'destroy'])->name('academic.class.teachers.destroy');
        Route::post('edit', [ClassesAssignmentController::class, 'edit'])->name('academic.class.teachers.edit');
        Route::get('assign/{uuid}', [ClassesAssignmentController::class, 'assign'])->name('academic.class.teachers.assign');

    });





    Route::group(['prefix'=>'subjects-assignment'], function(){

        Route::get('', [SubjectsAssignmentController::class, 'index'])->name('academic.subjects.assignment.index');
        Route::post('store', [SubjectsAssignmentController::class, 'store'])->name('academic.subjects.assignment.store');
        Route::get('datatables', [SubjectsAssignmentController::class, 'datatable'])->name('academic.subjects.assignment.datatable');
        Route::delete('destroy', [SubjectsAssignmentController::class, 'destroy'])->name('academic.subjects.assignment.destroy');
        Route::post('edit', [SubjectsAssignmentController::class, 'edit'])->name('academic.subjects.assignment.edit');

        Route::post('fetch-links', [SubjectsAssignmentController::class, 'getStreams'])->name('academic.subjects.streams.subjects.fetch');

        Route::group(['prefix'=>'student'], function(){

        Route::get('', [AssignStudentSubjects::class, 'classWiseStreamIndex'])->name('students.subjects.assignment.general.index');
        Route::post('load', [AssignStudentSubjects::class, 'classWiseStreamLoad'])->name('students.subjects.assignment.general.load');
        Route::post('mono-update', [AssignStudentSubjects::class, 'monoUpdate'])->name('students.subjects.assignment.general.mono.update');

        });

        Route::group(['prefix'=>'streamwise'], function(){

            Route::get('', [StreamSubjectsAssignmentController::class, 'index'])->name('streams.subjects.assignment.general.index');
            Route::post('mono-update', [StreamSubjectsAssignmentController::class, 'monoUpdate'])->name('streams.subjects.assignment.general.mono.update');

        });
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

    Route::post('assign/{uuid}', [RolesController::class, 'assignPermissionStore'])->name('configurations.security.roles.assignment.store');

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
 Route::post('/saveprofile',[GeneralController::class, 'profile'])->name('configuration.general.profile');

//  school logo edit
Route::post('edit-image/{school_id}', [GeneralController::class, 'updateSchoolLogo'])->name('school.image.update');

});


});



/* USER MANAGEMENT MODULE */

Route::group(['prefix'=>'user-management'], function(){

    Route::get('', [UsersController::class, 'index'])->name('user.management.index');
    Route::get('datatables', [UsersController::class, 'datatable'])->name('users.management.datatable');
    Route::post('store', [UsersController::class, 'store'])->name('users.management.store');
    Route::get('registration/{uuid?}', [UsersController::class, 'registration'])->name('users.management.registration');
    Route::post('edit', [UsersController::class, 'edit'])->name('users.management.edit');
     /* new edit */




     /* end it */

    Route::delete('destroy', [UsersController::class, 'destroy'])->name('users.management.destroy');

    Route::group(['prefix'=>'profile'], function(){

        Route::group(['prefix'=>'{uuid}'], function(){

            Route::group(['prefix'=>'personal-info'], function(){
                Route::get('edit-req', [UsersController::class, 'editUser'])->name('users.management.registration.edit.req');
                Route::get('', [UsersController::class, 'profile'])->name('users.management.profile');
                Route::post('update-basic', [UsersController::class, 'updateUserBasic'])->name('users.management.profile.basic.update');
                // Route::post('store', [UsersController::class, 'contactPeopleStore'])->name('users.management.profile.profile.contact.people.store');
                // Route::post('edit', [UsersController::class, 'contactPeopleEdit'])->name('users.management.profile.profile.contact.people.edit');
            });
            Route::group(['prefix'=>'contact-people'], function(){
                Route::get('', [UsersController::class, 'contactPeopleIndex'])->name('users.management.profile.contact.people.index');
                Route::get('datatable', [UsersController::class, 'contactPeopleDatatable'])->name('users.management.profile.profile.contact.people.datatable');
                Route::post('store', [UsersController::class, 'contactPeopleStore'])->name('users.management.profile.profile.contact.people.store');
                Route::post('edit', [UsersController::class, 'contactPeopleEdit'])->name('users.management.profile.profile.contact.people.edit');
            });

            Route::group(['prefix'=>'subjects-allocated'], function(){
                Route::get('', [UsersController::class, 'subjectsAllocatedIndex'])->name('users.management.profile.subjects.allocated.index');
                Route::get('datatable', [UsersController::class, 'subjectsAllocatedDatatable'])->name('users.management.profile.subjects.allocated.datatable');
                Route::post('store', [UsersController::class, 'subjectsAllocatedStore'])->name('users.management.profile.subjects.allocated.store');
                Route::post('edit', [UsersController::class, 'subjectsAllocatedEdit'])->name('users.management.profile.subjects.allocated.edit');
            });

            Route::group(['prefix'=>'attachments'], function(){
                Route::get('', [UsersController::class, 'attachmentsIndex'])->name('users.management.profile.attachments.index');
                Route::get('datatable', [UsersController::class, 'attachmentsDatatable'])->name('users.management.profile.attachments.datatable');
                Route::post('store', [UsersController::class, 'attachmentsStore'])->name('users.management.profile.attachments.store');
                Route::post('edit', [UsersController::class, 'attachmentsEdit'])->name('users.management.profile.attachments.edit');
            });

            Route::group(['prefix'=>'login-history'], function(){
                Route::get('', [UsersController::class, 'loginHistory'])->name('users.management.profile.login.history');
            });

            // Profile route
        Route::get('', [UsersController::class, 'myProfile'])->name('users.management.my.profile');





        });




    });



});


/* RESULTS */

Route::group(['prefix'=>'marking'], function(){

    // Route::middleware('check.module.access:8')->group(function () {
    // });

    Route::get('', [ ResultsController::class,'index'])->name('results.index');

    Route::group(['prefix'=>'upload'], function(){


        Route::group(['prefix'=>'excel'], function(){

            Route::get('{uuid}/{specific_uuid}/{grade_group_id}/{sbjct_id}/{class_id}/{stream_id?}', [ UploadController::class,'index'])->name('results.template.export.index');
            Route::get('template', [ UploadController::class,'exportTemplate'])->name('results.template.export');
            Route::post('store', [ResultsUploadExcelController::class, 'importResults'])->name('results.sytem.excel.import');
            // route to store results by excel
            Route::post('store-excel', [ResultsUploadExcelController::class, 'store'])->name('results.sytem.excel.store');
            Route::post('pre-import', [ResultsUploadExcelController::class, 'preImportMarks'])->name('results.sytem.excel.import.preview');
            Route::get('excel-errors', [ResultsUploadExcelController::class, 'downloadExcelValidationErrors'])->name('results.sytem.excel.import.errors');
            Route::get('completed-marks-count', [ResultsUploadExcelController::class, 'getCompleteMarksCount'])->name('results.sytem.excel.completed.marks');
            Route::get('drafts-marks-count', [ResultsUploadExcelController::class, 'getDraftMarksCount'])->name('results.sytem.excel.drafts.marks');
            Route::get('incomplete-marks-count', [ResultsUploadExcelController::class, 'getIncompleteMarksCount'])->name('results.sytem.excel.incomplete.marks');

            Route::group(['prefix'=>'incomplete-marking'], function(){

            Route::get('editable-view/{year_id}/{semester_id}/{class_id}/{stream_id}/{exam_id}/{subject_id}', [ResultsUploadExcelController::class, 'getIncompleteMarkingEditable'])->name('results.sytem.excel.incomplete.marks.editable');

            // my route to fetch update the list of studnets in the results tsble.
            Route::post('/load/update/students/results', [UploadController::class, 'compareStudents'])->name('load.update.students.results');




            Route::post('editable-datatable', [ResultsUploadExcelController::class, 'getIncompleteMarkingEditableDatatable'])->name('results.sytem.excel.incomplete.marks.editable.datatable');

            Route::post('finalize', [ResultsUploadExcelController::class, 'finalize'])->name('results.incomplete.finalize');
            Route::post('revert', [ResultsUploadExcelController::class, 'revert'])->name('results.complete.revert');
            Route::post('edit', [ResultsUploadExcelController::class, 'edit'])->name('results.incomplete.edit');

            Route::post('updateScore', [ResultsUploadExcelController::class, 'updateScore'])->name('results.incomplete.update');
            Route::post('updateDraftScore', [UploadController::class, 'updateDraftsMark'])->name('results.drafts.score.update');

            });



        });





    });




    /* REPORTS */

    Route::group(['prefix'=>'reports'], function(){


        Route::get('', [ ReportsController::class,'newIndex'])->name('results.reports.index');
        Route::get('generate_report', [ ReportsController::class,'generateClassReport'])->name('results.reports.class.report.exam.generate');
        Route::post('query', [UploadController::class, 'templateQuery'])->name('results.sytem.entry.template.query');
        Route::post('store', [UploadController::class, 'store'])->name('results.sytem.entry.store');
        // Route::get('registration/{uuid?}', [UploadController::class, 'registration'])->name('users.management.registration');
        Route::post('edit', [UploadController::class, 'edit'])->name('results.uploads.edit');
        Route::post('load', [ReportsController::class, 'load'])->name('results.reports.load');
        Route::post('datatable', [ReportsController::class, 'datatable'])->name('results.reports.datatable');



        Route::group(['prefix'=>'generate'], function(){

            Route::post('', [ReportsController::class,'generateExamReport'])->name('results.reports.generate.report.post');
            Route::get('', [ReportsController::class,'generatedExamReportsIndex'])->name('results.reports.generated.reports.index');
            Route::post('datatable', [ReportsController::class,'generatedExamReportsDatatable'])->name('results.reports.generated.reports.datatable');
            Route::get('generated_indrive/{uuid}', [ReportsController::class,'generatedExamReportViewIndrive'])->name('results.reports.generated.reports.view.indrive');

                  // printing the indrive
                  Route::get('generated_indrive_print/{uuid}', [ReportsController::class,'generatedExamReportViewIndrivePrint'])->name('results.reports.generated.reports.view.indrive.print');

                //   printing a  single student report
                Route::get('generated_indrive_print_single/{generated_exam_report_uuid}/{student_uuid}', [ReportsController::class,'generatedExamReportViewIndrivePrintSingle'])->name('results.reports.generated.reports.view.indrive.print.single');



        });


        Route::group(['prefix'=>'report-character-assessments/{uuid}'], function(){

            Route::get('', [CharacterAssessmentsAllocationController::class,'index'])->name('character.assessments.index');
            Route::post('', [ReportsController::class,'generatedExamReportsIndex'])->name('character.assessments.reports.index');

            Route::post('edit', [CharacterAssessmentsAllocationController::class,'edit'])->name('character.assessments.reports.edit');
            Route::get('create', [CharacterAssessmentsAllocationController::class,'create'])->name('character.assessments.reports.create');

            Route::get('datatable', [CharacterAssessmentsAllocationController::class,'datatable'])->name('character.assessments.report.datatable');
            Route::get('download-excel', [CharacterAssessmentsAllocationController::class,'generateTemplate'])->name('character.assessments.excel.template');

            Route::post('excel-import', [CharacterAssessmentsAllocationController::class,'characterAssessmentImport'])->name('character.assessments.excel.import');

        });

        Route::get('ca-excel-errors', [CharacterAssessmentsAllocationController::class, 'downloadExcelValidationErrors'])->name('character.assessments.excel.import.errors');


        Route::group(['prefix'=> 'escalate'], function(){

            Route::get('index/{uuid}', [ReportsController::class,'escalationIndex'])->name('results.reports.escalation.index');
            Route::post('', [ReportsController::class,'oneLevelUp'])->name('results.reports.escalation.top');
            Route::post('hm-comment', [ReportsController::class,'hmCommentUpdate'])->name('results.reports.escalation.hm.comment');
            Route::post('academic-view', [ReportsController::class,'academicEscalatedReportView'])->name('results.reports.escalation.report.academic.view');



        });



        /* LOAD REPORTS DYNAMIC */



        Route::group(['prefix'=>'published'], function(){

            Route::get('', [ResultsLoaderController::class,'index'])->name('results.reports.loader.index');
            Route::post('', [ResultsLoaderController::class,'loader'])->name('results.reports.loader.load');
            // Route::post('datatable', [ResultsLoaderController::class,'generatedExamReportsDatatable'])->name('results.reports.generated.reports.datatable');
            // Route::get('generated_indrive/{uuid}', [ResultsLoaderController::class,'generatedExamReportViewIndrive'])->name('results.reports.generated.reports.view.indrive');

            /* SINGLE EXAM TYPE ALL SUBJECTS */
            Route::post('preview-report', [ResultsLoaderController::class,'singleExamTypeReport'])->name('results.reports.generated.single.exam.report.pdf');

             // hm
             Route::get('index/{uuid}', [ReportsController::class,'publishIndex'])->name('results.reports.publish.index');

        });



        /* END REPORT */

        // Route::group(['prefix'=>'generate'], function(){
        //     Route::post('', [ReportsController::class,'generateExamReport'])->name('results.reports.generate.report.post');
        //     Route::get('', [ReportsController::class,'generatedExamReportsIndex'])->name('results.reports.generated.reports.index');
        //     Route::post('datatable', [ReportsController::class,'generatedExamReportsDatatable'])->name('results.reports.generated.reports.datatable');
        //     Route::get('generated_indrive/{uuid}', [ReportsController::class,'generatedExamReportViewIndrive'])->name('results.reports.generated.reports.view.indrive');


        // });


        Route::group(['prefix'=>'single-exam'], function(){

            Route::get('{student_id}/{exam_id}/{class_id}/{semester_id}/{academic_year}/{elevel}/{stream_id?}', [ReportsController::class, 'singeExamReportPdf'])->name('reports.single.exam.pdf')->where('stream_id', '.*');

        });


        Route::group(['prefix'=>'multiple-exams'], function(){

            Route::get('{student_id}/{exam_id}/{class_id}/{semester_id}/{academic_year}/{elevel}/{stream_id?}', [ReportsController::class, 'multipleExamReportPdf'])->name('reports.multiple.exam.pdf')->where('stream_id', '.*');

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

Route::post('streams', [UploadController::class, 'streams'])->name('classes.streams.fetch');
Route::post('get-streams', [ClassesAssignmentController::class, 'getStreams'])->name('academic.class.streams.fetch');
Route::post('get-sects', [ReligionController::class, 'getSects'])->name('religion.sect.links');
Route::post('get-sects-now', [ReligionController::class, 'getSects'])->name('religion.sect.fetch');


// });

});


require __DIR__.'/auth.php';
