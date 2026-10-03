<?php

namespace Config;

use App\Filters\AuthFilter;
use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Filter aliases.
     */
    public array $aliases = [
        'csrf'         => CSRF::class,
        'toolbar'      => DebugToolbar::class,
        'honeypot'     => Honeypot::class,
        'invalidchars' => InvalidChars::class,
        'secureheaders'=> SecureHeaders::class,

        // Custom authentication filter
        'auth'         => AuthFilter::class,
    ];

    /**
     * Required filters run on every request.
     */
    public array $required = [
        'before' => [
            // 'forcehttps',
            // 'pagecache',
        ],

        'after' => [
            // 'pagecache',
            // 'performance',
            // 'toolbar',
        ],
    ];

    /**
     * Global filters.
     */
    public array $globals = [
        'before' => [
            // Protect POST forms against CSRF attacks
            'csrf',

            // Uncomment if you want these filters globally
            // 'honeypot',
            // 'invalidchars',
        ],

        'after' => [
            // Enable during development if needed
            // 'toolbar',

            // Recommended when deploying with HTTPS
            // 'secureheaders',
        ],
    ];

    /**
     * HTTP method filters.
     */
    public array $methods = [];

    /**
     * Route-specific filters.
     */
    public array $filters = [];
}