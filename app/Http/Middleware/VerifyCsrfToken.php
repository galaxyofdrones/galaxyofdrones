<?php

namespace Koodilab\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * {@inheritdoc}
     */
    protected $except = [];
}
