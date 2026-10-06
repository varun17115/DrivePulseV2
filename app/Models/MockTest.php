<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'test_code',
        'total_questions',
        'score',
        'percentage',
        'result',
        'time_taken_seconds',
        'question_answers',
        'taken_at',
    ];

    protected function casts(): array
    {
        return [
            'question_answers' => 'array',
            'percentage' => 'decimal:2',
            'taken_at' => 'datetime',
        ];
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
