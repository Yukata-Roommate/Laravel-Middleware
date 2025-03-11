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
    protected Closure $next;

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
     * handle middleware
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->request = $request;
        $this->next    = $next;

        $this->prepare();

        $this->prepareHandle();

        if (!$this->isRunHandle()) return $this->next();

        return $this->runHandle();
    }

    /**
     * prepare middleware
     *
     * @return void
     */
    protected function prepare(): void {}

    /**
     * prepare middleware handle
     *
     * @return void
     */
    protected function prepareHandle(): void {}

    /**
     * whether run middleware handle
     *
     * @return bool
     */
    protected function isRunHandle(): bool
    {
        return true;
    }

    /**
     * run middleware handle
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    abstract protected function runHandle(): Response;

    /*----------------------------------------*
     * Terminate
     *----------------------------------------*/

    /**
     * terminate middleware
     *
     * @param \Illuminate\Http\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->request  = $request;
        $this->response = $response;

        $this->prepare();

        $this->prepareTerminate();

        if (!$this->isRunTerminate()) return;

        $this->runTerminate();
    }

    /**
     * prepare middleware terminate
     *
     * @return void
     */
    protected function prepareTerminate(): void {}

    /**
     * whether run middleware terminate
     *
     * @return bool
     */
    protected function isRunTerminate(): bool
    {
        return true;
    }

    /**
     * run middleware terminate
     *
     * @return void
     */
    protected function runTerminate(): void {}
}
