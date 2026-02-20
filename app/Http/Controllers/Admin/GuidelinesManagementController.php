<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeGuideline;
use Illuminate\Http\Request;

class GuidelinesManagementController extends Controller
{
    /**
     * Display all grade guidelines.
     */
    public function index()
    {
        $guidelines = GradeGuideline::all();

        return view('admin.guidelines.index', compact('guidelines'));
    }

    /**
     * Edit a specific grade guideline.
     */
    public function edit(string $grade)
    {
        $validGrades = ['grade_9_10', 'grade_11', 'grade_12'];

        if (!in_array($grade, $validGrades)) {
            abort(404);
        }

        $guideline = GradeGuideline::getByGrade($grade);

        // Create default if doesn't exist
        if (!$guideline) {
            $guideline = GradeGuideline::create([
                'grade' => $grade,
                'title' => $this->getDefaultTitle($grade),
                'content' => '<p>Content coming soon...</p>',
            ]);
        }

        return view('admin.guidelines.edit', compact('guideline'));
    }

    /**
     * Update a grade guideline.
     */
    public function update(Request $request, string $grade)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $guideline = GradeGuideline::where('grade', $grade)->firstOrFail();

        $guideline->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.guidelines')
            ->with('success', 'Guidelines updated successfully.');
    }

    /**
     * Get default title for a grade.
     */
    private function getDefaultTitle(string $grade): string
    {
        return match($grade) {
            'grade_9_10' => 'College Preparation for Grades 9 & 10',
            'grade_11' => 'College Preparation for Grade 11',
            'grade_12' => 'College Preparation for Grade 12',
            default => 'College Preparation Guidelines',
        };
    }
}
