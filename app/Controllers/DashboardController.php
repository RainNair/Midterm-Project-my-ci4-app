<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $role = session()->get('role') === 'admin' ? 'admin' : 'staff';

        return view('Dashboard', [
            'role' => $role,
            'isAdmin' => $role === 'admin',
        ]);
    }
}
