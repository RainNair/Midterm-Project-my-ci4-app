<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $requiredRoles = is_array($arguments) ? $arguments : [];
        $currentRole = (string) session()->get('role');

        if ($requiredRoles && ! in_array($currentRole, $requiredRoles, true)) {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'You do not have permission to access that page.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
