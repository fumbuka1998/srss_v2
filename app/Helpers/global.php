<?php

use App\Models\ExamSchedule;
use App\Models\ExamScheduleClassStreamSubject;
use App\Models\Grade;
use App\Models\Result;
use App\Models\ResultDraft;
use App\Models\SubjectTeacher;

class GlobalHelpers
{

    /* DATE FORMARTS */
    function toMysqlDateFormat($inputDate)
    {
        $mysqlDate = date("Y-m-d", strtotime($inputDate));
        return  $mysqlDate;
    }


    function getDraftedMarksCount()
    {

        $user = auth()->user();
        $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();

        if (auth()->user()->hasRole('Admin')) {

            $drafts = ResultDraft::join('academic_years', 'result_drafts.academic_year_id', '=', 'academic_years.id')
                ->join('semesters', 'result_drafts.semester_id', '=', 'semesters.id')
                ->join('school_classes', 'school_classes.id', '=', 'result_drafts.class_id')
                ->join('subjects', 'result_drafts.subject_id', '=', 'subjects.id')
                ->join('streams', 'streams.id', '=', 'result_drafts.stream_id')
                ->join('exams', 'exams.id', '=', 'result_drafts.exam_id')
                ->select('academic_years.name as acnm', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.id as semester_name')
                ->groupBy(['result_drafts.academic_year_id', 'result_drafts.semester_id', 'result_drafts.class_id', 'result_drafts.exam_id', 'result_drafts.subject_id', 'result_drafts.stream_id'])
                ->get()->count();
        } else {

            $drafts = ResultDraft::join('academic_years', 'result_drafts.academic_year_id', '=', 'academic_years.id')
                ->join('semesters', 'result_drafts.semester_id', '=', 'semesters.id')
                ->join('school_classes', 'school_classes.id', '=', 'result_drafts.class_id')
                ->join('subjects', 'result_drafts.subject_id', '=', 'subjects.id')
                ->join('streams', 'streams.id', '=', 'result_drafts.stream_id')
                ->join('exams', 'exams.id', '=', 'result_drafts.exam_id')
                ->select('academic_years.name as acnm', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.id as semester_name')
                ->where(function ($query) use ($assignments) {
                    foreach ($assignments as $assignment) {
                        $query->orWhere(function ($innerQuery) use ($assignment) {
                            $innerQuery->where('result_drafts.class_id', $assignment->class_id)
                                ->where('result_drafts.stream_id', $assignment->stream_id)
                                ->where('result_drafts.subject_id', $assignment->subject_id);
                        });
                    }
                })

                ->groupBy(['result_drafts.academic_year_id', 'result_drafts.semester_id', 'result_drafts.class_id', 'result_drafts.exam_id', 'result_drafts.subject_id', 'result_drafts.stream_id'])
                ->get()->count();
        }

        return $drafts;
    }


    function getIncompletedMarksCount()
    {

        $user = auth()->user();
        $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();

        if (auth()->user()->hasRole('Admin')) {

            $incompletes = Result::join('academic_years', 'results.academic_year_id', '=', 'academic_years.id')
                ->join('semesters', 'results.semester_id', '=', 'semesters.id')
                ->join('school_classes', 'school_classes.id', '=', 'results.class_id')
                ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                ->leftjoin('streams', 'streams.id', '=', 'results.stream_id')
                ->join('exams', 'exams.id', '=', 'results.exam_id')
                ->where('results.status', 'PENDING')
                ->select('academic_years.name as acnm', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.id as semester_name')
                ->groupBy(['results.academic_year_id', 'results.semester_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id'])
                ->get()->count();
        } else {

            $incompletes = Result::join('academic_years', 'results.academic_year_id', '=', 'academic_years.id')
                ->join('semesters', 'results.semester_id', '=', 'semesters.id')
                ->join('school_classes', 'school_classes.id', '=', 'results.class_id')
                ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                ->leftjoin('streams', 'streams.id', '=', 'results.stream_id')
                ->join('exams', 'exams.id', '=', 'results.exam_id')
                ->where('results.status', 'PENDING')
                ->select('academic_years.name as acnm', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.id as semester_name')
                ->where(function ($query) use ($assignments) {
                    foreach ($assignments as $assignment) {
                        $query->orWhere(function ($innerQuery) use ($assignment) {
                            $innerQuery->where('results.class_id', $assignment->class_id)
                                ->where('results.stream_id', $assignment->stream_id)
                                ->where('results.subject_id', $assignment->subject_id);
                        });
                    }
                })
                ->groupBy(['results.academic_year_id', 'results.semester_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id'])
                ->get()->count();
        }

        return $incompletes;
    }


    function completedMarksCount()
    {

        $user = auth()->user();
        $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();

        if (auth()->user()->hasRole('Admin')) {

            $complete = Result::join('academic_years', 'results.academic_year_id', '=', 'academic_years.id')
                ->join('semesters', 'results.semester_id', '=', 'semesters.id')
                ->join('school_classes', 'school_classes.id', '=', 'results.class_id')
                ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                ->leftjoin('streams', 'streams.id', '=', 'results.stream_id')
                ->join('exams', 'exams.id', '=', 'results.exam_id')
                ->where('results.status', 'COMPLETED')
                ->select('academic_years.name as acnm', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.id as semester_name')
                ->groupBy(['results.academic_year_id', 'results.semester_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id'])
                ->get()->count();
        } else {


            $complete = Result::join('academic_years', 'results.academic_year_id', '=', 'academic_years.id')
                ->join('semesters', 'results.semester_id', '=', 'semesters.id')
                ->join('school_classes', 'school_classes.id', '=', 'results.class_id')
                ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                ->leftjoin('streams', 'streams.id', '=', 'results.stream_id')
                ->join('exams', 'exams.id', '=', 'results.exam_id')
                ->where('results.status', 'COMPLETED')
                ->select('academic_years.name as acnm', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.id as semester_name')
                ->where(function ($query) use ($assignments) {
                    foreach ($assignments as $assignment) {
                        $query->orWhere(function ($innerQuery) use ($assignment) {
                            $innerQuery->where('results.class_id', $assignment->class_id)
                                ->where('results.stream_id', $assignment->stream_id)
                                ->where('results.subject_id', $assignment->subject_id);
                        });
                    }
                })
                ->groupBy(['results.academic_year_id', 'results.semester_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id'])
                ->get()->count();
        }

        return $complete;
    }



    function waitingForMarkingCount()
    {

        $todays_date = date('Y-m-d');
        $user = auth()->user();
        $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();

        if ($user->hasRole('Admin')) {

            $ids = ExamScheduleClassStreamSubject::pluck('id');

            return $exam_schedules =   ExamSchedule::join('academic_years', 'academic_years.id', '=', 'exam_schedules.academic_year_id')
                ->join('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
                ->join('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
                ->join('exams', 'exams.id', '=', 'exam_schedules.exam_id')
                ->leftjoin('users', 'users.id', '=', 'exam_schedules.created_by')
                ->leftjoin('exam_schedule_class_stream_subjects', 'exam_schedule_class_stream_subjects.exam_schedule_id', '=', 'exam_schedules.id')
                ->leftjoin('school_classes', 'exam_schedule_class_stream_subjects.class_id', '=', 'school_classes.id')
                ->leftjoin('streams', 'exam_schedule_class_stream_subjects.stream_id', '=', 'streams.id')
                ->leftjoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                ->select(
                    'academic_years.name as acdmc_name',
                    'exam_schedules.id as ex_id',
                    'semesters.name as semi_name',
                    'grade_groups.name as grade_name',
                    'grade_groups.uuid as grade_group_uuid',
                    'exams.name as exam_name',
                    'exam_schedules.uuid as exschedule_uuid',
                    'users.firstname',
                    'users.lastname',
                    'streams.name as stream_name',
                    'streams.id as stream_id',
                    'marking_to',
                    'exam_schedule_class_stream_subjects.uuid as blogic_uuid',
                    'school_classes.name as class_name',
                    'school_classes.id as sclass_id',
                    'subjects.id as sbjct_id',
                    'subjects.name as sbjct_name'
                )
                ->where('marking_from', '<=', $todays_date)
                ->whereIn('exam_schedule_class_stream_subjects.uuid', $ids)
                ->orderBy('ex_id', 'desc')
                ->get()->count();
        } else {


            return $exam_schedules = ExamSchedule::join('academic_years', 'academic_years.id', '=', 'exam_schedules.academic_year_id')
                ->join('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
                ->join('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
                ->join('exams', 'exams.id', '=', 'exam_schedules.exam_id')
                ->leftjoin('users', 'users.id', '=', 'exam_schedules.created_by')
                ->leftjoin('exam_schedule_class_stream_subjects', 'exam_schedule_class_stream_subjects.exam_schedule_id', '=', 'exam_schedules.id')
                ->leftjoin('school_classes', 'exam_schedule_class_stream_subjects.class_id', '=', 'school_classes.id')
                ->leftjoin('streams', 'exam_schedule_class_stream_subjects.stream_id', '=', 'streams.id')
                ->leftjoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                ->select(
                    'academic_years.name as acdmc_name',
                    'exam_schedules.id as ex_id',
                    'semesters.name as semi_name',
                    'grade_groups.name as grade_name',
                    'grade_groups.uuid as grade_group_uuid',
                    'exams.name as exam_name',
                    'exam_schedules.uuid as exschedule_uuid',
                    'users.firstname',
                    'users.lastname',
                    'streams.name as stream_name',
                    'streams.id as stream_id',
                    'marking_to',
                    'exam_schedule_class_stream_subjects.uuid as blogic_uuid',
                    'school_classes.name as class_name',
                    'school_classes.id as sclass_id',
                    'subjects.id as sbjct_id',
                    'subjects.name as sbjct_name'
                )
                ->where(function ($query) use ($assignments) {
                    foreach ($assignments as $assignment) {
                        $query->orWhere(function ($innerQuery) use ($assignment) {
                            $innerQuery->where('exam_schedule_class_stream_subjects.class_id', $assignment->class_id)
                                ->where('exam_schedule_class_stream_subjects.stream_id', $assignment->stream_id)
                                ->where('exam_schedule_class_stream_subjects.subject_id', $assignment->subject_id);
                        });
                    }
                })
                ->where('marking_from', '<=', $todays_date)
                ->orderBy('ex_id', 'desc')
                ->get()->count();
        }
    }


    function upcomingForMarkingCount()
    {

        $todays_date = date('Y-m-d');
        $exam_schedules = ExamSchedule::where('start_from', '>', $todays_date)->where('end_on', '>', $todays_date);
        $user = auth()->user();
        $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();
        $ids = ExamScheduleClassStreamSubject::pluck('id');

        if ($user->getRoleNames()[0] == 'Admin') {

            return  $exam_schedules = ExamSchedule::join('academic_years', 'academic_years.id', '=', 'exam_schedules.academic_year_id')
                ->join('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
                ->join('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
                ->join('exams', 'exams.id', '=', 'exam_schedules.exam_id')
                ->leftjoin('users', 'users.id', '=', 'exam_schedules.created_by')
                ->leftjoin('exam_schedule_class_stream_subjects', 'exam_schedule_class_stream_subjects.exam_schedule_id', '=', 'exam_schedules.id')
                ->leftjoin('school_classes', 'exam_schedule_class_stream_subjects.class_id', '=', 'school_classes.id')
                ->leftjoin('streams', 'exam_schedule_class_stream_subjects.stream_id', '=', 'streams.id')
                ->leftjoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                ->select(
                    'academic_years.name as acdmc_name',
                    'semesters.name as semi_name',
                    'grade_groups.name as grade_name',
                    'exams.name as exam_name',
                    'exam_schedules.uuid as exschedule_uuid',
                    'users.firstname',
                    'users.lastname',
                    'streams.name as stream_name',
                    'streams.id as stream_id',
                    'marking_to',
                    'exam_schedules.id as x_id',
                    'exam_schedule_class_stream_subjects.uuid as blogic_uuid',
                    'school_classes.name as class_name',
                    'school_classes.id as sclass_id',
                    'subjects.id as sbjct_id',
                    'subjects.name as sbjct_name'
                )
                ->where('start_from', '>', $todays_date)->where('end_on', '>', $todays_date)
                ->whereIn('exam_schedule_class_stream_subjects.uuid', $ids)
                ->orderBy('x_id', 'desc')
                ->get()->count();
        } else {

            return  $exam_schedules = ExamSchedule::join('academic_years', 'academic_years.id', '=', 'exam_schedules.academic_year_id')
                ->join('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
                ->join('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
                ->join('exams', 'exams.id', '=', 'exam_schedules.exam_id')
                ->leftjoin('users', 'users.id', '=', 'exam_schedules.created_by')
                ->leftjoin('exam_schedule_class_stream_subjects', 'exam_schedule_class_stream_subjects.exam_schedule_id', '=', 'exam_schedules.id')
                ->leftjoin('school_classes', 'exam_schedule_class_stream_subjects.class_id', '=', 'school_classes.id')
                ->leftjoin('streams', 'exam_schedule_class_stream_subjects.stream_id', '=', 'streams.id')
                ->leftjoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                ->select(
                    'academic_years.name as acdmc_name',
                    'semesters.name as semi_name',
                    'grade_groups.name as grade_name',
                    'exams.name as exam_name',
                    'exam_schedules.uuid as exschedule_uuid',
                    'users.firstname',
                    'users.lastname',
                    'streams.name as stream_name',
                    'streams.id as stream_id',
                    'marking_to',
                    'exam_schedules.id as x_id',
                    'exam_schedule_class_stream_subjects.uuid as blogic_uuid',
                    'school_classes.name as class_name',
                    'school_classes.id as sclass_id',
                    'subjects.id as sbjct_id',
                    'subjects.name as sbjct_name'
                )
                ->where('start_from', '>', $todays_date)->where('end_on', '>', $todays_date)
                ->where(function ($query) use ($assignments) {
                    foreach ($assignments as $assignment) {
                        $query->orWhere(function ($innerQuery) use ($assignment) {
                            $innerQuery->where('exam_schedule_class_stream_subjects.class_id', $assignment->class_id)
                                ->where('exam_schedule_class_stream_subjects.stream_id', $assignment->stream_id)
                                ->where('exam_schedule_class_stream_subjects.subject_id', $assignment->subject_id);
                        });
                    }
                })
                ->orderBy('x_id', 'desc')
                ->get()->count();
        }
    }



    function isValidScore($input)
    {
        if ($input == null) {
            return false;
        }
        $pattern = '/^(?:[0-9]+(?:\.[0-9]+)?|[sxSX])$/';
        if (preg_match($pattern, $input)) {
            return $input;
        } else {
            return false;
        }
    }


    function getIncompleteMarkingCount()
    {

        $count = Result::where('status', 'PENDING')->get()->count();
        return $count;
    }



    function getCompletedMarkingCount()
    {

        $count = Result::where('status', 'COMPLETED')->get()->count();
        return $count;
    }


    function getDraftsMarkingCount()
    {

        $count = ResultDraft::get()->count();
        return $count;
    }




    /* TO 4/5/2005  */

    function toSlashDateFormat($inputDate)
    {
        $slashDate = date("m/d/Y", strtotime($inputDate));
        return  $slashDate;
    }

    function marksToPercentage($score, $exam_info)
    {

        if ($score == 'x' || $score == 's' || $score == '-') {
            return 'N/A';
        }

        $percentage = $score / $exam_info->total_marks * 100;
        $rounded_percentage = round($percentage);

        return $rounded_percentage;
    }

    function getGrade($score, $elevel, $exam_info, $group_id)
    {
        // $grades = Grade::where('education_level_id', $e_level)->get();

        // return 1;
        $data = [];

        if ($score == 'x' || $score == 's' || $score == '-') {
            $data['grade'] = 'N/A';
            $data['remarks'] = 'N/A';
            $data['points'] = 'N/A';

            return $data;
        }

        $percentage = $score / $exam_info->total_marks * 100;

        $rounded_percentage = round($percentage);
        $grades = Grade::where('group_id', $group_id)->where('education_level_id', $elevel)->get();

        foreach ($grades as $key => $grade) {
            if ($rounded_percentage >= $grade->from && $rounded_percentage <= $grade->to) {
                // Assign grade and remarks
                $data['grade'] = $grade->name;
                $data['remarks'] = $grade->remarks;
                $data['points'] = $grade->points;
                break;
            }
        }
        return $data;
    }

    // mine
    function getGrade_mine($score, $elevel, $exam_info, $group_id)
    {
        $data = []; // Initialize the $data array

        // Convert score to lowercase
        $score = strtolower($score);

        // Check if score is 'x', 's', or '-'
        if ($score == 'x' || $score == 's' || $score == '-') {
            $data['grade'] = 'N/A';
            $data['remarks'] = 'N/A';
            $data['points'] = 'N/A';

            return $data;
        }

        // Convert score to numeric
        $score = floatval($score);

        // Check if score is a valid numeric value
        if (!is_numeric($score)) {
            $data['grade'] = 'N/A';
            $data['remarks'] = 'Invalid score';
            $data['points'] = 'N/A';

            return $data;
        }

        $percentage = $score / $exam_info->total_marks * 100;

        $rounded_percentage = round($percentage);
        $grades = Grade::where('group_id', $group_id)->where('education_level_id', $elevel)->get();

        foreach ($grades as $key => $grade) {
            if ($rounded_percentage >= $grade->from && $rounded_percentage <= $grade->to) {
                // Assign grade and remarks
                $data['grade'] = $grade->name;
                $data['remarks'] = $grade->remarks;
                $data['points'] = $grade->points;
                break; // No need to continue looping
            }
        }
        return $data;
    }


    function getAverageGrade_pure($rounded_percentage, $elevel, $group_id)
    {

        // $grades = Grade::where('education_level_id', $e_level)->get();

        // return 1;
        $data = []; // Initialize the $data array

        if ($rounded_percentage == 'x' || $rounded_percentage == 's') {
            $data['grade'] = 'N/A';
            $data['remarks'] = 'N/A';
            $data['points'] = 'N/A';

            return $data;
        }

        $grades = Grade::where('group_id', $group_id)->where('education_level_id', $elevel)->get();

        foreach ($grades as $key => $grade) {
            $data['remarks'] = 'N/A';
            if ($rounded_percentage >= $grade->from && $rounded_percentage <= $grade->to) {
                // Assign grade and remarks
                $data['grade'] = $name = $grade->name;
                $data['remarks'] = $grade->remarks;
                $data['points'] = $this->getGradePoints(strtoupper($name), $elevel);


                break; // No need to continue looping
            }
        }

        return $data;
    }

    function getAverageGrade($rounded_percentage, $elevel, $group_id)
{
    $data = [
        'grade' => '', // Initialize 'grade' key
        'remarks' => 'N/A',
        'points' => 'N/A'
    ];

    if ($rounded_percentage == 'x' || $rounded_percentage == 's') {
        return $data; // Return early if rounded percentage is 'x' or 's'
    }

    $grades = Grade::where('group_id', $group_id)->where('education_level_id', $elevel)->get();

    foreach ($grades as $key => $grade) {
        if ($rounded_percentage >= $grade->from && $rounded_percentage <= $grade->to) {
            // Assign grade and remarks
            $data['grade'] = $grade->name;
            $data['remarks'] = $grade->remarks;
            $data['points'] = $this->getGradePoints(strtoupper($grade->name), $elevel);
            break; // No need to continue looping
        }
    }

    return $data;
}


    public function getGradePoints($grade, $elevel)
    {

        if ($elevel == 3) {

            $grade_POINTS = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'F' => 5];
            return $grade_POINTS[$grade];
        } elseif ($elevel == 2) {

            $grade_POINTS = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'S' => 6, 'F' => 7];
            return $grade_POINTS[$grade];
        }
    }



    function generateDivisions($elevel, $points)
    {
        $data = [];
        $points_count = count($points);
        sort($points);

        if ($elevel == 3) {

            $sum = array_sum(array_slice($points, 0, 7));

            if ($points_count >= 7) {

                if ($sum >= 7  && $sum <= 17) {
                    $data['division']  = 1;
                    $data['code']  = 'I';
                    $data['points'] = $sum;
                    $data['remarks']  = 'EXCELLENT';
                }

                if ($sum >= 18  && $sum <= 21) {
                    $data['division']  = 2;
                    $data['code']  = 'II';
                    $data['points'] = $sum;
                    $data['remarks']  = 'VERY GOOD';
                }


                if ($sum >= 22  && $sum <= 25) {
                    $data['division']  = 3;
                    $data['code']  = 'III';
                    $data['points'] = $sum;
                    $data['remarks']  = 'GOOD';
                }

                if ($sum >= 26  && $sum <= 33) {
                    $data['division']  = 4;
                    $data['code']  = 'IV';
                    $data['points'] = $sum;
                    $data['remarks']  = 'SATISFACTORY';
                }

                if ($sum >= 34  && $sum <= 35) {
                    $data['division']  = 0;
                    $data['code']  = '0';
                    $data['points'] = $sum;
                    $data['remarks']  = 'FAIL';
                }
            } else {

                $data['division']  = 'N/A';
                $data['code']  = 'N/A';
                $data['points'] = 'N/A';
                $data['remarks']  = 'N/A';
            }
        } elseif ($elevel == 2) {

            $sum = array_sum(array_slice($points, 0, 3));

            if ($points_count == 3) {

                if ($sum >= 3 && $sum <= 9) {

                    $data['division']  = 1;
                    $data['code']  = 'I';
                    $data['points'] = $sum;
                    $data['remarks']  = 'EXCELLENT';
                }

                if ($sum >= 10 && $sum <= 12) {

                    $data['division']  = 2;
                    $data['code']  = 'II';
                    $data['points'] = $sum;
                    $data['remarks']  = 'VERY GOOD';
                }

                if ($sum >= 13 && $sum <= 17) {

                    $data['division']  = 3;
                    $data['code']  = 'III';
                    $data['points'] = $sum;
                    $data['remarks']  = 'GOOD';
                }

                if ($sum >= 18 && $sum <= 19) {

                    $data['division']  = 4;
                    $data['code']  = 'IV';
                    $data['points'] = $sum;
                    $data['remarks']  = 'SATISFACTORY';
                }

                if ($sum >= 20 && $sum <= 21) {

                    $data['division']  = 0;
                    $data['code']  = 0;
                    $data['points'] = $sum;
                    $data['remarks']  = 'FAIL';
                }
            } elseif ($points_count < 3) {

                $data['division']  = 'N/A';
                $data['code']  = 'N/A';
                $data['points'] = 'N/A';
                $data['remarks']  = 'N/A';
            }
        }else{
            $data['division']  = 'N/A';
                $data['code']  = 'N/A';
                $data['points'] = 'N/A';
                $data['remarks']  = 'N/A';
        }


        return $data;
    }


    function generateAvg($array)
    {

        $avg = 0;
        $sum  = 0;
        $under = count($array);

        foreach ($array as $key => $arr) {
            $sum += $arr;
        }

        if ($sum) {
            $avg = round($sum / $under);
        }



        return $avg ? $avg  : '-';
    }



    function numberPad($value, $pads)
    {
        return str_pad($value, $pads, '0', STR_PAD_LEFT);
    }



    function ageCalculator($dob)
    {

        $dobDate = new DateTime($dob);
        $currentDate = new DateTime();
        $ageInterval = $currentDate->diff($dobDate);
        $age = $ageInterval->y;
        return $age;
    }



    function base64url_encode($data)
    {
        $cipher = env('OPENSSL_ENCRYPTION_ALGORITHM', 'aes-128-cbc');
        $key = bin2hex(env('APP_KEY', '8tP4jmk80AucbtiM+Ic94/qU55C/19YnBHvORpeI+1I='));

        // Generating Random Characters every-time this function is Called
        //    $n = 16;
        //    $randomString = '';
        //    for ($i = 0; $i < $n; $i++) {
        //        $index = rand(0, strlen($key) - 1);
        //        $randomString .= $key[$index];
        //    }
        //
        //
        //    $iv = $randomString;

        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        global $tag;
        $cipherData = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);

        $encoded = base64_encode($iv . $cipherData);
        // Replacing some characters which may Result from Encoding
        $encodedModified = strtr($encoded, '+/=', '-_,');
        return $encodedModified;
    }

    function base64url_decode($data)
    {
        // Check for Invalid Formats and data
        if ($data == null || strlen($data) <= 16) {
            redirect()->back();
        }

        $cipher = env('OPENSSL_ENCRYPTION_ALGORITHM', 'aes-128-cbc');
        $key = bin2hex(env('APP_KEY', '8tP4jmk80AucbtiM+Ic94/qU55C/19YnBHvORpeI+1I='));

        $encodedModified = strtr($data, '-_,', '+/=');
        $decoded = base64_decode($encodedModified);

        $encrypted = substr($decoded, 16);
        $iv = substr($decoded,  0, 16);

        $original_data = openssl_decrypt("$encrypted", $cipher, $key, OPENSSL_RAW_DATA, $iv);

        return $original_data;
    }
}
