<?php

namespace AlexMasterov\TwigExtension;

use Psr\Http\Message\UriInterface;

final class AbsoluteUrlGenerator
{
    /**
     * @param UriInterface $uri
     *
     * @return string
     */
    public function __invoke(UriInterface $uri)
    {
        $host = $uri->getHost();
        $path = $uri->getPath();

        if (empty($host)) {
            return $path;
        }

        $port = $uri->getPort();
        if (!empty($port)) {
            $host .= ":{$port}";
        }

        $scheme = $uri->getScheme();

        return "{$scheme}://{$host}{$path}";
    }
}
