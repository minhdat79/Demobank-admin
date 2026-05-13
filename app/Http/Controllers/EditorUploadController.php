// app/Http/Controllers/EditorUploadController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorUploadController extends Controller
{
    public function ckImage(Request $request)
    {
        // CKEditor 5 SimpleUploadAdapter gửi field tên "upload"
        $request->validate([
            'upload' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120', // 5MB
        ]);

        $file = $request->file('upload');

        // đổi tên file an toàn
        $name = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('posts', $name, 'public'); // storage/app/public/posts/...

        // Đảm bảo đã tạo symlink: php artisan storage:link
        return response()->json([
            'url' => asset('storage/'.$path), // CKEditor mong đợi { url: "..." }
        ]);
    }
}
