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
            'grade_9_10' => 'High School (Grades 9 & 10)',
            'grade_11' => 'High School (Grade 11)',
            'grade_12' => 'High School (Grade 12)',
            'gap_year' => 'Gap Year',
            'community_college' => 'Community College',
            'undergraduate' => 'Undergraduate',
            'graduate' => 'Graduate',
            default => 'Unknown',
        };
    }
}
