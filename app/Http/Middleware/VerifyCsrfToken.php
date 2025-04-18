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
        // REMOVE or COMMENT OUT the line below if present,
        // because our /api/saved-recipes routes are now in web.php
        // and need CSRF protection.
        // 'api/*',
    ];
}
