<?php

namespace Asmaster\TwigExtension\tests;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig_ExtensionInterface;
use Asmaster\TwigExtension\Psr7UriExtension;

class Psr7UriExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ServerRequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $request;

    public function setUp()
    {
        $this->request = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testExtension()
    {
        $extension = new Psr7UriExtension($this->request);

        $this->assertInstanceOf(Twig_ExtensionInterface::class, $extension);
        $this->assertSame('psr7_uri', $extension->getName());
    }

    /**
     * @dataProvider getFunctions()
     */
    public function testExtensionFunctions($function)
    {
        $extension = new Psr7UriExtension($this->request);

        $functions = array_map(
            function ($function) {
                return $function->getName();
            },
            $extension->getFunctions()
        );

        $this->assertContains($function, $functions);
    }

    public function getFunctions()
    {
        return [[
            'absolute_url',
            'relative_path'
       ]];
    }

    /**
     * @dataProvider getGenerateAbsoluteUrlData()
     */
    public function testGenerateAbsoluteUrl($path, $expected, $scheme, $host, $port, $basePath)
    {
        $request = $this->request
            ->expects($this->any())
            ->method('getUri')
            ->willReturn($this->createUri($scheme, $host, $port, $basePath));

        $extension = new Psr7UriExtension($this->request);
        $absoluteUrl = $extension->generateAbsoluteUrl($path);

        $this->assertEquals($expected, $absoluteUrl);
    }

    public function getGenerateAbsoluteUrlData()
    {
        return [
            ['/foo.png', '/foo.png', 'http', '', null, '/foo.png'],
            ['/foo.png', 'http://localhost/foo.png', 'http', 'localhost', null, '/'],
            ['/foo.png', 'http://localhost/foo.png', 'http', 'localhost', null, '/foo.png'],
            ['foo.png', 'http://localhost/foo/foo.png', 'http', 'localhost', null, '/foo'],
            ['foo.png', 'http://localhost/bar/foo.png', 'http', 'localhost', null, '/bar'],
            ['foo.png', 'http://localhost/foo/bar/foo.png', 'http', 'localhost', null, '/foo/bar'],
            ['/foo.png', 'http://localhost:8080/foo.png', 'http', 'localhost', 8080, '/'],
            ['/foo.png', 'https://localhost/foo.png', 'https', 'localhost', null, '/'],
            ['/', 'http://localhost/', 'http', 'localhost', null, '/'],
            ['//', '//', 'http', 'localhost', null, '/']
        ];
    }

    /**
     * @dataProvider getGenerateRelativeUrlData()
     */
    public function testGenerateRelativeUrl($path, $expected, $scheme, $host, $port, $basePath)
    {
        $request = $this->request
            ->expects($this->any())
            ->method('getUri')
            ->willReturn($this->createUri($scheme, $host, $port, $basePath));

        $extension = new Psr7UriExtension($this->request);
        $relativePath = $extension->generateRelativePath($path);

        $this->assertEquals($expected, $relativePath);
    }

    public function getGenerateRelativeUrlData()
    {
        return [
            ['/a/b/c/foo.png', 'foo.png', 'http', 'localhost', null, '/a/b/c/d'],
            ['/a/b/foo.png', '../foo.png', 'http', 'localhost', null, '/a/b/c/d'],
            ['/a/b/c/d', '', 'http', 'localhost', null, '/a/b/c/d'],
            ['/a/b/c/', './', 'http', 'localhost', null, '/a/b/c/d'],
            ['/a/b/c/other', 'other', 'http', 'localhost', null, '/a/b/c/d'],
            ['/a/b/z/foo.png', '../z/foo.png', 'http', 'localhost', null, '/a/b/c/d'],
            ['/a/b/c/this:that', './this:that', 'http', 'localhost', null, '/a/b/c/d'],
            ['/a/b/c/foo/this:that', 'foo/this:that', 'http', 'localhost', null, '/a/b/c/d'],
            ['/', '/', 'http', 'localhost', null, ''],
            ['//', '//', 'http', 'localhost', null, '']
        ];
    }

    /**
     * @param string $scheme
     * @param string $host
     * @param string $port
     * @param string $basePath
     *
     * @return UriInterface $uri New request URI to use.
     */
    protected function createUri($scheme, $host, $port, $basePath)
    {
        $uri = $this->getMockBuilder(UriInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uri->expects($this->any())->method('getScheme')->willReturn($scheme);
        $uri->expects($this->any())->method('getHost')->willReturn($host);
        $uri->expects($this->any())->method('getPort')->willReturn($port);
        $uri->expects($this->any())->method('getPath')->willReturn($basePath);

        return $uri;
    }
}
