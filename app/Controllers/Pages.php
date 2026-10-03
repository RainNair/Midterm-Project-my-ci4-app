<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title'   => 'Home',
            'heading' => 'Welcome to CodeIgniter 4',
        ];

        return view('pages/home', $data);
    }

    public function about()
    {
        $data = [
            'title'   => 'About Us',
            'heading' => 'About Our Application',
            'message' => 'This application demonstrates routes, controllers, views, and models.',
        ];

        return view('pages/about', $data);
    }
	
	public function profile(int $studentId)
{
    $data = [
        'title'     => 'Student Profile',
        'studentId' => $studentId,
    ];

    return view('pages/profile', $data);
}
}