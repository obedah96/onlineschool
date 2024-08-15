<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blogs;

class test extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // حفظ الصورة في المجلد public/images/blogs
        $imagePath = $request->image->store('blogs', 'public');

        // حفظ البيانات في قاعدة البيانات
        Blog::create([
            'title' => $request->title,
            'description' => $request->description,
            'ImagePath' => $imagePath,
        ]);

        // إرجاع استجابة JSON
        return response()->json([
            'message' => 'تم حفظ الصورة والبيانات بنجاح',
            'image_path' => asset('storage/' . $imagePath)
        ], 201);
    }
}
