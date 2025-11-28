<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function landing()
    {
        return view('landing-pages');
    }

    public function home()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }

    public function services()
    {
        return view('service');
    }

    public function whyUs()
    {
        return view('why_us');
    }

    public function contact()
    {
        return view('contact');
    }

    public function jobListing()
    {
        return view('job_listing');
    }

    public function jobDetail($id)
    {
        // In real app, fetch job from database
        // For now, return view with default job data
        return view('job_detail');
    }

    public function jobApplication()
    {
        return view('form-jobs');
    }

    public function profile()
    {
        // For frontend testing - no auth required
        return view('profile');
    }

    public function history()
    {
        // For frontend testing - no auth required
        return view('history');
    }
}
