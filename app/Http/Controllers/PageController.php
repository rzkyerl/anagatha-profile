<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
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
}
