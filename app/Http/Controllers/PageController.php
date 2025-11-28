<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function landing()
    {
        return view('pages.landing-pages');
    }

    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function services()
    {
        return view('pages.service');
    }

    public function whyUs()
    {
        return view('pages.why_us');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function jobListing()
    {
        return view('jobs.job_listing');
    }

    public function jobDetail($id)
    {
        // In real app, fetch job from database
        // For now, return view with default job data
        return view('jobs.job_detail');
    }

    public function jobApplication()
    {
        return view('jobs.form-jobs');
    }

    public function profile()
    {
        // For frontend testing - no auth required
        return view('pages.profile');
    }

    public function history()
    {
        // For frontend testing - no auth required
        return view('jobs.history');
    }
}
