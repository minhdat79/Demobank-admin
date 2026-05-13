<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index()
    {
        $items = Job::latest('publish_date')->paginate(15);
        return view('admin.jobs.index', compact('items'));
    }

    public function create()
    {
        $item = new Job();
        // mặc định mở
        $item->status = 'open';
        return view('admin.jobs.form', compact('item'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('banner_image')) {
            $name = Str::uuid().'.'.$request->file('banner_image')->getClientOriginalExtension();
            $data['banner_image'] = $request->file('banner_image')
                ->storeAs('uploads/banners', $name, 'public');
        }

        $job = Job::create($data);

        return redirect()->route('admin.jobs.edit', $job)
            ->with('ok', 'Đã tạo tin tuyển dụng.');
    }

    public function edit(Job $job)
    {
        $item = $job;
        return view('admin.jobs.form', compact('item'));
    }

    public function update(Request $request, Job $job)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('banner_image')) {
            if ($job->banner_image && Storage::disk('public')->exists($job->banner_image)) {
                Storage::disk('public')->delete($job->banner_image);
            }
            $name = Str::uuid().'.'.$request->file('banner_image')->getClientOriginalExtension();
            $data['banner_image'] = $request->file('banner_image')
                ->storeAs('uploads/banners', $name, 'public');
        }

        $job->update($data);

        return back()->with('ok', 'Đã cập nhật.');
    }

    public function destroy(Job $job)
    {
        if ($job->banner_image && Storage::disk('public')->exists($job->banner_image)) {
            Storage::disk('public')->delete($job->banner_image);
        }
        $job->delete();
        return back()->with('ok', 'Đã xoá.');
    }

    private function validatedData(Request $request): array
    {
        $rules = [
            'title'            => 'required|string|max:255',
            'department'       => 'nullable|string|max:255',
            'location'         => 'nullable|string|max:255',
            'employment_type'  => 'nullable|string|max:255',
            'salary_min'       => 'nullable|numeric',
            'salary_max'       => 'nullable|numeric',
            'publish_date'     => 'nullable|date',
            'close_date'       => 'nullable|date',
            'apply_url'        => 'nullable|string|max:1000',
            'summary'          => 'nullable|string',
            'description'      => 'nullable|string',
            'seo_title'        => 'nullable|string|max:255',
            'seo_description'  => 'nullable|string|max:255',
            'is_published'     => 'nullable|boolean',
            'status'           => 'required|in:open,closed',   // ✅ chọn đóng/mở
            'banner_image'     => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ];

        $messages = [
            'title.required'    => 'Vui lòng nhập Chức danh / Vị trí.',
            'status.required'   => 'Vui lòng chọn Trạng thái.',
            'status.in'         => 'Trạng thái không hợp lệ.',
            'banner_image.image'=> 'Banner phải là tệp ảnh.',
            'banner_image.mimes'=> 'Banner chỉ nhận: jpg, jpeg, png, gif, webp.',
            'banner_image.max'  => 'Banner tối đa 5MB.',
        ];

        $attributes = [
            'title'           => 'Chức danh / Vị trí',
            'status'          => 'Trạng thái',
            'banner_image'    => 'Ảnh banner',
        ];

        $v = $request->validate($rules, $messages, $attributes);
        $v['is_published'] = $request->boolean('is_published');
        return $v;
    }
}
