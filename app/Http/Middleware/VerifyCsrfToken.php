<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        'sanctum/csrf-cookie',
        'admin/*',
        '*/api/*',
        'tours/*/reviews',
        'reviews/*/like',
        'reviews/*/reply',
        'reviews/*',
        'replies/*/like',
        'tours/create-reservation',
        'favorites/toggle/*'
    ];
} 