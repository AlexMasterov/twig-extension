<?php

namespace AlexMasterov\TwigExtension;

use Psr\Http\Message\UriInterface;

final class RelativePathGenerator
{
    /**
     * @param UriInterface $uri
     * @param string $path
     *
     * @return string
     */
    public function __invoke(UriInterface $uri, $path)
    {
        $basePath = $uri->getPath();
        if ($path === $basePath) {
            return '';
        }

        $baseParts = explode('/', $basePath, -1);
        $pathParts = explode('/', $path);

        foreach ($baseParts as $i => $segment) {
            if (isset($pathParts[$i]) && $segment === $pathParts[$i]) {
                unset($baseParts[$i], $pathParts[$i]);
            } else {
                break;
            }
        }

        $path = str_repeat('../', count($baseParts)) . implode('/', $pathParts);

        if (empty($path)) {
            return './';
        }

        if (empty($baseParts) && false !== strpos(current($pathParts), ':')) {
            $path = "./{$path}";
        }

        return $path;
    }
}
