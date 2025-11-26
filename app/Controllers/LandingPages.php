<?php

namespace App\Controllers;

class LandingPages extends BaseController
{
    public function index()
    {
        return view('landingpages/index');
    }
}
