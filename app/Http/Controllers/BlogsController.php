<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // app/Http/Controllers/BlogController.php

    public function index(Request $request)
        {   
            if ($request->has('id')) {
                // إذا كان هناك قيمة لـ id
                $blog = Blogs::find($request->id);
            
                if ($blog) {
                    // إذا وجدت المدونة
                    $jsonData = [
                        'status' => 'success',
                        'data' => $blog,
                    ];
                } else {
                    // إذا لم توجد المدونة
                    $jsonData = [
                        'status' => 'error',
                        'message' => 'المدونة غير موجودة',
                    ];
                }
            } else {
                // إذا لم يتم تمرير أي قيمة لـ id
                $blogs = Blogs::paginate(5);
                $jsonData = [
                    'status' => 'success',
                    'data' => $blogs,
                ];
            }
            
            return response()->json($blogs);
            
        }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
     
    }
    

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $file_extintion=$request->image->getClientOriginalExtension();
        $file_name=time().'.'.$file_extintion;
        $path='images/blogs';
        // حفظ الصورة في المجلد public/images/blogs
        $request->image->move($path,$file_name);
        

        // حفظ البيانات في قاعدة البيانات
        Blogs::create([
            'title' => $request->title,
            'description' => $request->description,
            'ImagePath' => $file_name,
        ]);

        // إرجاع استجابة JSON
        return response()->json([
            'message' => 'تم حفظ الصورة والبيانات بنجاح',
            
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $reques)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
{   
        $blog = Blogs::find($request->id);
        if (!$blog) {
            return response()->json(['message' => 'لم يتم العثور على المدونة'], 404);
        }

        // التحقق من صحة البيانات الواردة
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
        ]);
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($blog->ImagePath) {
                $oldImagePath = public_path('images/blogs/' . $blog->ImagePath);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                    Storage::delete($oldImagePath);
                    $blog->ImagePath = null;
                    $blog->save();
                }
            }
        }
            // حفظ الصورة الجديدة
        $file_extintion = $request->image->getClientOriginalExtension();
        $file_name = time() . '.' . $file_extintion;
        $path = 'images/blogs';
        $request->image->move($path, $file_name);
    
            // تحديث مسار الصورة في قاعدة البيانات
        $blog->ImagePath = $file_name;
        $blog->title = $request->title;
        $blog->description = $request->description;
    
        $blog->save();
        // إرجاع استجابة JSON تحتوي على البيانات المحدثة للمدونة
        return response()->json([
            'message' => 'تم تحديث المدونة بنجاح',
            'blog' => $blog
        ]);
    }


        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Request $request)
        {
            // التحقق من وجود المدونة
            $blog = Blogs::find($request->id);
            if (!$blog) {
                return response()->json(['message' => 'المدونة غير موجودة'], 404);
            }

            // حذف المدونة
            $blog->forceDelete();

            return response()->json(['message' => 'تم حذف المدونة بنجاح']);
        }
}
