<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     * Os proxies confiáveis para este aplicativo
     *
     * @var array
     */
    protected $proxies;
    
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
    /**
     * The current proxy header mappings.
     *
     * @var array
     * protected $headers = [
     *   Request::HEADER_FORWARDED => 'FORWARDED',
     *   Request::HEADER_X_FORWARDED_FOR => 'X_FORWARDED_FOR',
     *   Request::HEADER_X_FORWARDED_HOST => 'X_FORWARDED_HOST',
     *   Request::HEADER_X_FORWARDED_PORT => 'X_FORWARDED_PORT',
     *   Request::HEADER_X_FORWARDED_PROTO => 'X_FORWARDED_PROTO',
     * ];
     */
}
