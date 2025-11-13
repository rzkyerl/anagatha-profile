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
        return redirect()->to(url('/#about'));
    }

    public function services()
    {
        return redirect()->to(url('/#services'));
    }

    public function whyUs()
    {
        return redirect()->to(url('/#why-us'));
    }

    public function contact()
    {
        return redirect()->to(url('/#contact'));
    }
}
