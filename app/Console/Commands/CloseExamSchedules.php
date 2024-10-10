<?php

namespace App\Console\Commands;

use App\Models\ExamSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseExamSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam-schedules:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close exam schedules if end date is less than today.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Closing exam schedules......');

        $exam_schedules = ExamSchedule::leftJoin('academic_years', 'academic_years.id', 'exam_schedules.academic_year_id')
        ->leftJoin('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
        ->leftJoin('exams', 'exams.id', '=', 'exam_schedules.exam_id')
        ->leftJoin('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
        ->select(
            'exam_schedules.id',
            'exams.name as exam_name',
            'semesters.name as semester_name',
            'exam_schedules.start_from',
            'exam_schedules.end_on',
            'marking_from',
            'marking_to',
            'exam_schedules.status',
            'academic_years.name as acdmc_year_name',
            'grade_groups.name as grade_group_name'
        )
        ->get();

        foreach ($exam_schedules as $schedule) {
            if (Carbon::parse($schedule->end_on)->isPast() && $schedule->status !== 'closed') {
                // Update the status to 'closed'
                ExamSchedule::where('id', $schedule->id)->update(['status' => 'Closed']);
                $this->info("Exam Schedule ID {$schedule->id} closed.");
            }
        }

        $this->info('Exam schedules closed successfully.');

        // return 0;
    }
}
