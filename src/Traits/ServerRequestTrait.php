<?php

namespace Asmaster\TwigExtension\Traits;

use Psr\Http\Message\ServerRequestInterface;

trait ServerRequestTrait
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }
}
