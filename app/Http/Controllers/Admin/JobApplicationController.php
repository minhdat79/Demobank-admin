<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    // Hiển thị danh sách hồ sơ
    public function index()
    {
        // Lấy danh sách hồ sơ, kèm theo thông tin công việc, sắp xếp mới nhất lên đầu
        $applications = JobApplication::with('job')->latest()->paginate(15);
        return view('admin.applications.index', compact('applications'));
    }

    // Xử lý nút Duyệt / Từ chối
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $application = JobApplication::findOrFail($id);
        $application->update([
            'status' => $request->status
        ]);

        $msg = $request->status == 'approved' ? 'Đã DUYỆT hồ sơ thành công!' : 'Đã TỪ CHỐI hồ sơ!';
        return back()->with('ok', $msg);
    }
}