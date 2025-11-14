<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated=$request->validate([
            'student_code'=>'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
            'career'=>'required',
            'enrollment_year'=>'required',
            'photo'=>'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path=$request->file('photo')->store('students', 'public');
            $validated['photo']=$path;
        }
        Student::create($validated);
        return redirect()->route('students.index')->with('success','Estudiante creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student=Student::findOrFail($id);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student=Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $student=Student::findOrFail($id);

        $validated=$request->validate([
            'student_code'=>'required|unique:students,student_code,'.$student->id,
            'first_name'=>'required|string|max:50',
            'last_name'=>'required|string|max:50',
            'email'=>'required|email|unique:students,email,'.$student->id,
            'phone'=>'required|string|max:15',
            'address'=>'required|string|max:255',
            'career'=>'required|string|max:100',
            'enrollment_year'=>'required',
            'photo'=>'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $path=$request->file('photo')->store('students', 'public');
            $validated['photo']=$path;
        }
        $student->update($validated);
        return redirect()->route('students.index')->with('success','Estudiante actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student=Student::findOrFail($id);

        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->delete();
        return redirect()->route('students.index')->with('success','Estudiante eliminado exitosamente');
    }
}
