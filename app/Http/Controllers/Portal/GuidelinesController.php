<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\GradeGuideline;
use Illuminate\Http\Request;

class GuidelinesController extends Controller
{
    /**
     * Display guidelines selection page.
     */
    public function index()
    {
        $user = auth()->user();
        $guidelines = GradeGuideline::all()->keyBy('grade');

        return view('portal.guidelines', compact('user', 'guidelines'));
    }

    /**
     * Display guidelines for a specific grade.
     */
    public function show(string $grade)
    {
        $user = auth()->user();
        $validGrades = ['grade_9_10', 'grade_11', 'grade_12'];

        if (!in_array($grade, $validGrades)) {
            abort(404);
        }

        $guideline = GradeGuideline::getByGrade($grade);
        $guidelines = GradeGuideline::all()->keyBy('grade');

        return view('portal.guidelines-show', compact('user', 'guideline', 'grade', 'guidelines'));
    }
}
