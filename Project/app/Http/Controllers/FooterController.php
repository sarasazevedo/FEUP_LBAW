<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function mainFeatures()
    {
        return view('pages.main_features');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function contacts()
    {
        return view('pages.contacts');
    }
}