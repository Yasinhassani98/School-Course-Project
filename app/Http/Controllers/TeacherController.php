<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderby('created_at', 'desc')->paginate(10);
        return view('teachers.index', ['teachers' => $teachers]);
    }
    public function create()
    {
        return view('teachers.create', [
            'classrooms' => Classroom::with('level')->get(),
            'subjects' => Subject::with('level')->get()
        ]);
    }
    public function show()
    {
        return view('teachers.show');
    }
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image',
            'name' => 'required|min:3|max:255',
            'classroom_ids' => 'nullable|string',
            'subject_ids' => 'nullable|string',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'specialization' => 'nullable|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        $teacher = Teacher::create([
            ...$request->except('image', 'classroom_ids', 'subject_ids'),
            'image' => $request->hasFile('image')
                ? $request->file('image')->store('Teachers', 'public')
                : null,
        ]);

        if ($request->filled('classroom_ids')) {
            $classroom_ids = array_filter(explode(',', $request->classroom_ids));
            $teacher->classrooms()->attach($classroom_ids);
        }
        if ($request->filled('subject_ids')) {
            $subject_ids = array_filter(explode(',', $request->subject_ids));
            $teacher->subjects()->attach($subject_ids);
        }
        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully');
    }
    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', [
            'teacher' => $teacher,
            'classrooms' => Classroom::with('level')->get(),
            'subjects' => Subject::with('level')->get()
        ]);
    }
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'image' => 'nullable|image',
            'name' => 'required|min:3|max:255',
            'classroom_ids' => 'nullable|string',
            'subject_ids' => 'nullable|string',
            'email' => 'required|email|unique:teachers,email' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'specialization' => 'nullable|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        $teacher->update([
            ...$request->except('image', 'classroom_ids', 'subject_ids'),
        ]);
        if($request->hasFile('image')) {
            // Delete old image if exists
            if($teacher->image) {
                Storage::disk('public')->delete($teacher->image);
            }
            $image = $request->file('image');
            $path = $image->store('Teachers', 'public');
            $teacher->image = $path;
        }

        if ($request->filled('classroom_ids')) {
            $classroom_ids = json_decode($request->classroom_ids);
            $classroom_ids = collect($classroom_ids)->pluck('value')->toArray();
            $teacher->classrooms()->sync($classroom_ids);
        }
        if ($request->filled('subject_ids')) {
            $subject_ids = json_decode($request->subject_ids);
            $classroom_ids = collect($subject_ids)->pluck('value')->toArray();
            $teacher->subjects()->sync($subject_ids);
        }
    }
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully');
    }
}
