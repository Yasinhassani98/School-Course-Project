<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use App\Http\Requests\StoreMarkRequest;
use App\Http\Requests\UpdateMarkRequest;
use App\Models\Level;
use App\Models\Teacher;
use App\Notifications\GeneralNotification;
use App\Notifications\ResponseNotification;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MarkController extends Controller
{
    public function index()
    {
        $marks = Mark::with(['teacher', 'student', 'subject', 'classroom', 'academicYear'])
            ->latest()
            ->paginate(10);

        return view('admin.marks.index', compact('marks'));
    }
    public function create()
    {
        $levels = Level::all();
        $teachers = Teacher::all();
        $students = Student::with('classroom.level')
            ->whereHas('classroom.level')
            ->get();

        $subjects = Subject::with('level')
            ->whereHas('level')
            ->get();

        $classrooms = Classroom::with('level')
            ->whereHas('level')
            ->get();

        $academicYears = AcademicYear::all();

        return view('admin.marks.create', compact('teachers', 'students', 'subjects', 'classrooms', 'academicYears', 'levels'));
    }

    public function store(StoreMarkRequest $request)
    {
        $academicYear = AcademicYear::where('is_current', true)->firstOrFail();

        // Make sure teacher_id is set
        $teacherId = $request->input('teacher_id');
        $studentIds = $request->input('student_ids', []);
        $marks = $request->input('marks', []);
        $classroomId = $request->input('classroom_id');
        $subjectId = $request->input('subject_id');
        $note = $request->input('note');
        foreach ($studentIds as $index => $studentId) {
            if (!isset($marks[$index]) || $marks[$index] === '') {
                continue;
            }
            Mark::create([
                'teacher_id' => $teacherId, 
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'classroom_id' => $classroomId,
                'academic_year_id' => $academicYear->id,
                'mark' => $marks[$index],
                'note' => $note,
            ]);
            // Notification
            $student = Student::findOrFail($studentId);
            $parent = $student->parent;
            $subject = Subject::find($subjectId);
            $notification = new GeneralNotification(
                'New Mark Added',
                "A new mark has been added for {$subject->name}.",
                'info'
            );
            $student->user->notify($notification);
            $parent->user->notify($notification);
        }
        Auth::user()->notify(new ResponseNotification('success', 'Marks added successfully for classroom ' . Classroom::find($classroomId)->name . ' and subject ' . Subject::find($subjectId)->name));
        return redirect()
            ->route('admin.marks.index');
    }


    public function edit(Mark $mark): View
    {
        $teachers = Teacher::all();
        $students = Student::with('classroom.level')
            ->whereHas('classroom.level')
            ->get();

        $subjects = Subject::with('level')
            ->whereHas('level')
            ->get();

        $classrooms = Classroom::with('level')
            ->whereHas('level')
            ->get();

        $academicYears = AcademicYear::all();

        return view('admin.marks.edit', compact('teachers', 'mark', 'students', 'subjects', 'classrooms', 'academicYears'));
    }

    public function update(UpdateMarkRequest $request, Mark $mark): RedirectResponse
    {
        $academicYear = AcademicYear::where('is_current', true)->first();
        $data = array_merge($request->validated(), ['academic_year_id' => $academicYear->id]);
        $mark->update($data);

        // Notification
        $student = Student::findOrFail($mark->student_id);
        $parent = $student->parent;
        $subject = Subject::find($mark->subject_id);
        $notification = new GeneralNotification(
            'Mark Updated',
            "The mark has been updated for {$subject->name}.",
            'info'
        );
        $student->user->notify($notification);
        $parent->user->notify($notification);
        Auth::user()->notify(new ResponseNotification('success', 'Mark updated successfully'));

        return redirect()
            ->route('admin.marks.index');
    }

    public function destroy(Mark $mark): RedirectResponse
    {
        $mark->delete();
        Auth::user()->notify(new ResponseNotification('success', 'Mark deleted successfully'));
        return redirect()
            ->route('admin.marks.index');
    }
}
