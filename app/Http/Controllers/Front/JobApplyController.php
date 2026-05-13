<?php

namespace App\Http\Controllers\Front;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobApplyController extends Controller
{
    public function create(Job $job)
    {
       
        return view('front.jobs.apply2', compact('job'));
    }

    public function store(Request $request, Job $job)
    {
        $data = $request->validate([
            'cv'            => ['required','file','mimes:pdf,doc,docx','max:5120'],
            'avatar'        => ['nullable','image','max:4096'],
            'dob'           => ['required','date_format:d/m/Y'],
            'gender'        => ['required','in:male,female,other'],
            'preferred_loc' => ['required','string','max:255'],
            'salary'        => ['required','numeric','min:0'],
            'note'          => ['nullable','string','max:2000'],
        ]);

        $cvPath     = $request->file('cv')->store('applications/cv', 'public');
        $avatarPath = $request->file('avatar')?->store('applications/avatars', 'public');

    

        return back()->with('ok', 'Hồ sơ đã gửi thành công! Bộ phận tuyển dụng sẽ liên hệ bạn.');
    }
}
