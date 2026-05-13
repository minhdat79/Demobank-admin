<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function ckeditor(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ], [], ['upload' => 'Ảnh']);

        $file = $request->file('upload');
        $name = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/editor', $name, 'public');

        return response()->json(['url' => asset('storage/'.$path)]);
    }
}
