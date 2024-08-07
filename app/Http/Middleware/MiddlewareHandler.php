<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Configuration\Middleware;

final class MiddlewareHandler
{
    protected array $aliases = [
        'token' => TokenMiddleware::class
    ];

    public function __invoke(Middleware $middleware): Middleware
    {
        if ($this->aliases) {
            $middleware->alias($this->aliases);
        }
        return $middleware;
    }

}
