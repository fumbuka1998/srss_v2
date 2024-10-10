<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneratedExamReport extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $date = ['deleted_at'];

    protected $fillable = [
        'uuid',
        'class_id',
        'stream_id',
        'generated_by',
        'exam_report_id',
        'academic_year_id',
        'term_id',
        'for_my_grade',
        'exam_type_combination',
        'include_signature',
        'is_published',
        'have_es',
        'have_ca',
        'subject_type_combination',
        // 'status',
        'escalation_level_id'
        //not added to migration for_my_grade
    ];

    // have_ca    have_es            has no migration


}
