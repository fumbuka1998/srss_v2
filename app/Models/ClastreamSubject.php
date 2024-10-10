<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClastreamSubject extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $data = ['deleted_at'];

   protected $fillable = [
    'class_id',
    'stream_id',
    'subject_id'
    ];

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
