<?php

namespace App\Controllers;

class AboutController extends Controller
{
    public function about()
    {
        $data = [];
        $this->sendPage('about', $data);
    }
}
