<?php

namespace YukataRm\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base Middleware
 * 
 * @package YukataRm\Laravel\Middleware
 */
abstract class BaseMiddleware
{
    /*----------------------------------------*
     * Property
     *----------------------------------------*/

    /**
     * request
     * 
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * response
     * 
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected Response $response;

    /**
     * next closure
     * 
     * @var \Closure( \Illuminate\Http\Request ): \Symfony\Component\HttpFoundation\Response
     */
    private Closure $next;

    /**
     * next response
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function next(): Response
    {
        return ($this->next)($this->request);
    }

    /*----------------------------------------*
     * Handle
     *----------------------------------------*/

    /**
     * handle an incoming request
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->request = $request;
        $this->next    = $next;

        return $this->runHandle();
    }

    /**
     * run the middleware handle
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    abstract public function runHandle(): Response;

    /*----------------------------------------*
     * Terminate
     *----------------------------------------*/

    /**
     * terminate the middleware
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->request  = $request;
        $this->response = $response;

        $this->runTerminate();
    }

    /**
     * run the middleware terminate
     * 
     * @return void
     */
    public function runTerminate(): void {}
}
