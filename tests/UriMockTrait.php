<?php

namespace AlexMasterov\TwigExtension\Tests;

use Psr\Http\Message\UriInterface;

trait UriMockTrait
{
    /**
     * @param string $scheme
     * @param string $host
     * @param string $path
     * @param string $port
     *
     * @return UriInterface
     */
    private function createMockUri(
        $scheme = null,
        $host = null,
        $path = null,
        $port = null
    ) {
        $uri = $this->createMock(UriInterface::class);
        $uri->expects($this->any())->method('getScheme')->willReturn($scheme);
        $uri->expects($this->any())->method('getHost')->willReturn($host);
        $uri->expects($this->any())->method('getPath')->willReturn($path);
        $uri->expects($this->any())->method('getPort')->willReturn($port);

        return $uri;
    }
}
