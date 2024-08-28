<?php

namespace App\Http\Controllers;

use App\Models\subscribers;
use Illuminate\Http\Request;

class SubscribersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   if($request->has('id'))
        {
            $sub=subscribers::find($request->id);
            if($sub){
                $jsonData = [
                    'status' => 'success',
                    'data' => $sub,
                ];
            }
            else {
                // إذا لم توجد البيانات
                $jsonData = [
                    'status' => 'error',
                    'message' => 'البيانات غير موجودة',
                ];
                }
           
        }
        else{
                // إذا لم يتم تمرير أي قيمة لـ id
                $sub=subscribers::paginate(10);
                $jsonData = [
                    'status' => 'success',
                    'data' => $sub,
                ];
            }
            return response()->json($sub);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
        $rules = [
            'email' => 'required|email|unique:subscribers',
        ];

        // تخصيص الرسائل إذا كانت هناك أخطاء
        /*$messages = [
            'email.unique' => 'this user already exsist',
        ];*/

        // التحقق من صحة البيانات
        $validated = $request->validate($rules);
        if($validated){
        subscribers::create($validated);
        return response()->json(['message' => 'Subscriber created successfully.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {   
        
    }

    /**
     * Display the specified resource.
     */
    public function show(subscribers $subscribers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(subscribers $subscribers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {   $request->validate(['email' => 'required',]);
        
            $sub=subscribers::find($id);
            if(!$sub){
                return response()->json(['message'=>'the subscriber not found']);
            }
        else{
            $sub->email=
            $request->email;
            $sub->save();
            return response()->json(['message'=>'update done','sub'=>$sub]);
            }
            
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $sub=subscribers::find($request->id);
        if (!$sub) {
            return response()->json(['message' => 'the subscriber not exsist'], 404);
        }

        // حذف المدونة
        $sub->forceDelete();

        return response()->json(['message' => 'deleted done']);
    }
}