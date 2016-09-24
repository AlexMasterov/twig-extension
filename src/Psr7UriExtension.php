<?php

namespace AlexMasterov\TwigExtension;

use AlexMasterov\TwigExtension\AbsoluteUrlGenerator;
use AlexMasterov\TwigExtension\RelativePathGenerator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Twig_Extension;
use Twig_SimpleFunction;

final class Psr7UriExtension extends Twig_Extension
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return string The extension name
     */
    public function getName()
    {
        return 'psr7_uri';
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('absolute_url', [$this, 'generateAbsoluteUrl']),
            new Twig_SimpleFunction('relative_path', [$this, 'generateRelativePath'])
        ];
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function generateAbsoluteUrl($path = null)
    {
        $url = $this->absoluteUrl(
            $this->request->getUri()
        );

        if (null === $path) {
            return $url;
        }

        if ($this->isNetworkPath($path)) {
            return $path;
        }

        if (!$this->hasLeadingSlash($url)) {
            $url = rtrim($url, '/') . $path;
        }

        return $url;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function generateRelativePath($path)
    {
        if ($this->isNetworkPath($path) || !$this->hasLeadingSlash($path)) {
            return $path;
        }

        $path = $this->relativePath(
            $this->request->getUri(),
            $path
        );

        return $path;
    }

    /**
     * @param UriInterface $uri
     *
     * @return string
     */
    private function absoluteUrl(UriInterface $uri)
    {
        $generator = new AbsoluteUrlGenerator();
        $absoluteUrl = $generator($uri);

        return $absoluteUrl;
    }

    /**
     * @param UriInterface $uri
     *
     * @return string
     */
    private function relativePath(UriInterface $uri, $path)
    {
        $generator = new RelativePathGenerator();
        $relativePath = $generator($uri, $path);

        return $relativePath;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function isNetworkPath($path)
    {
        return false !== strpos($path, '://')
            || '//' === substr($path, 0, 2);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function hasLeadingSlash($path)
    {
        return isset($path[0]) && '/' === $path[0];
    }
}
