<?php

namespace App\Http\Controllers;

use App\Models\Cources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('id')) {
            
            $course = Cources::find($request->id);
        
            if ($course) {
                $jsonData = [
                    'status' => 'success',
                    'data' => $course,
                ];
            } 
            else {
                $jsonData = [
                    'status' => 'error',
                    'message' => 'course not found',
                ];
            }
        }
        else {
            $courses = Cources::paginate(5);
            $jsonData = [
                'status' => 'success',
                'data' => $courses,
            ];
        }
        return response()->json([$jsonData]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {   
        
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $file_extintion = $request->image->getClientOriginalExtension();
        $file_name = time() . '.' . $file_extintion;
        $path = 'courses/' . $file_name; // مسار نسبي داخل الدليل public

        // حفظ الصورة باستخدام حزمة Storage
        Storage::disk('public')->put($path, $request->image);
        $imageUrl = Storage::url($path);
        $course = Cources::create([
            'title' => $request->title,
            'teacher' => $request->teacher,
            'description' => $request->description,
            'image'=>Storage::url($path),
            'price'=>$request->price,
            'course_outline'=>$request->course_outline,
            'duration_in_session'=>$request->duration_in_session,
            'course_start_date'=>$request->course_start_date,
            'min_age'=>$request->min_age,
            'max_age'=>$request->max_age,
            
        ]);
        return response()->json([
                                'message' => 'Course created successfully'
                                ,'data'=>$course
                                ]);
    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cources $cources)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cources $cources)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $course = Cources::find($request->id);
        if (!$course) {
            return response()->json(['message' => 'course not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
        ]);
       if ($request->hasFile('image') && $course->image) {
        Storage::disk('public')->delete('courses/' . $course->image);
    }

    // حفظ الصورة الجديدة (إذا تم تحميل صورة جديدة)
    if ($request->hasFile('image')) {
        $path = 'courses/' . time() . '.' . $request->image->getClientOriginalExtension();
         Storage::disk('public')->put($path, $request->image);;

        // حفظ المسار الكامل للصورة في قاعدة البيانات
        $course->image = Storage::disk('public')->url($path);
        $course->title = $request->title;
        $course->teacher=$request->teacher;
        $course->description = $request->description;
        $course->price=$request->price;
        $course->course_outline=$request->course_outline;
        $course->duration_in_session=$request->duration_in_session;
        $course->course_start_date=$request->course_start_date;
        $course->min_age=$request->min_age;
        $course->max_age=$request->max_age;
    
        $course->save();
        // إرجاع استجابة JSON تحتوي على البيانات المحدثة للمدونة
        return response()->json([
            'message' => 'course updated',
            'course : ' => $course
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Request $request)
{
    $course = Cources::find($request->id);
    if (!$course) {
        return response()->json(['message' => 'course not found'], 404);
    }

    $imagePath = $course->image;

    if (Storage::disk('public')->exists($imagePath)) {
        Storage::disk('public')->delete($imagePath);
    }

    $course->forceDelete();
    return response()->json(['message' => 'course deleted']);
}
}
