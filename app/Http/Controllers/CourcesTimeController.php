<?php

namespace App\Http\Controllers;

use App\Models\Cources_time;
use App\Models\Cources;
use Illuminate\Http\Request;

class CourcesTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courseId=$request->course_id;
        $course_name=Cources::find($request->course_id);
        $dayOfMonth=$request->day_of_month;
        $courseTimes = Cources_time::where('course_id', $courseId)
                         ->where('day_of_month', $dayOfMonth)
                         ->get();
    return response()->json([
                            'course name'=>$course_name->title,
                            'data'=>$courseTimes,
                            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $course_time=Cources_time::create($request->only(['course_id', 'day_of_month', 'start_time','end_time']));
        $course_name=Cources::find($request->course_id);
        return response()->json([
                        'message'=>'course time created ',
                        'course_name:'=>$course_name->title,
                        'data'=>$course_time

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
    public function show(Cources_time $cources_time)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cources_time $cources_time)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $newTime=Cources_time::find($request->id);
        $newTime->start_time=$request->start_time;
        $newTime->end_time=$request->end_time;
        $newTime->save();
        return response()->json([
                                'message'=>'time updated',
                                'data'=>$newTime
                                ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $timeToDelete=Cources_time::find($request->id);
        if(!$timeToDelete){
            return response()->json(['message'=>'date not found']);
        }
        $timeToDelete->forceDelete();
        return response()->json(['message'=>'deleted done']);
    }
}
