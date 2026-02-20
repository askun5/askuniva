<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeGuideline extends Model
{
    protected $fillable = [
        'grade',
        'title',
        'content',
    ];

    /**
     * Get guideline by grade.
     */
    public static function getByGrade(string $grade): ?self
    {
        return static::where('grade', $grade)->first();
    }

    /**
     * Get the display name for the grade.
     */
    public function getGradeDisplayAttribute(): string
    {
        return match($this->grade) {
            'grade_9_10' => 'Grade 9 & 10',
            'grade_11' => 'Grade 11',
            'grade_12' => 'Grade 12',
            default => 'Unknown',
        };
    }
}
