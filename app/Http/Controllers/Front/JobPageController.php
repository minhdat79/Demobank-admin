<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobPageController extends Controller
{
    public function index(Request $request)
    {
        $q    = trim((string) $request->get('q', ''));
        $dep  = trim((string) $request->get('department', ''));
        $loc  = trim((string) $request->get('location', ''));
        $type = trim((string) $request->get('type', '')); // employment_type ở DB
        $tab  = $request->get('tab', 'open'); // open/closed/all

        $query = Job::query()
            ->when($q, function ($q1) use ($q) {
                $q1->where(function ($x) use ($q) {
                    $x->where('title', 'like', "%{$q}%")
                      ->orWhere('department', 'like', "%{$q}%")
                      ->orWhere('location', 'like', "%{$q}%");
                });
            })
            ->when($dep,  fn($q1) => $q1->where('department', $dep))
            ->when($loc,  fn($q1) => $q1->where('location',   $loc))
            ->when($type, fn($q1) => $q1->where('employment_type', $type))
            ->orderByDesc('publish_date');

      
        if ($tab === 'open') {
            $query->where(function ($qq) {
                $qq->whereNull('close_date')
                   ->orWhere('close_date', '>=', now()->startOfDay());
            });
        } elseif ($tab === 'closed') {
            $query->whereNotNull('close_date')
                  ->where('close_date', '<', now()->startOfDay());
        }

        $jobs = $query->paginate(12)->withQueryString();


        $departments = Job::query()->whereNotNull('department')->distinct()->pluck('department')->filter()->values();
        $locations   = Job::query()->whereNotNull('location')->distinct()->pluck('location')->filter()->values();
        $types       = Job::query()->whereNotNull('employment_type')->distinct()->pluck('employment_type')->filter()->values();

        return view('admin.jobs.index', [
            'items'        => $jobs,
            'q'           => $q,
            'dep'         => $dep,
            'loc'         => $loc,
            'type'        => $type,
            'departments' => $departments,
            'locations'   => $locations,
            'types'       => $types,
            'tab'         => $tab,
        ]);
    }

   
    public function show(Job $job)
    {
      
        return view('front.jobs.show', compact('job'));
    }
}
