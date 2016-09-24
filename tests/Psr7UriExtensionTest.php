<?php

namespace AlexMasterov\TwigExtension\Tests;

use AlexMasterov\TwigExtension\Psr7UriExtension;
use AlexMasterov\TwigExtension\Tests\UriMockTrait;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Twig_ExtensionInterface;

class Psr7UriExtensionTest extends TestCase
{
    use UriMockTrait;

    public function testThatExtensionIsValid()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $extension = new Psr7UriExtension($request);

        $this->assertInstanceOf(Twig_ExtensionInterface::class, $extension);
        $this->assertSame('psr7_uri', $extension->getName());
    }

    public function getFunctions()
    {
        return [[
            'absolute_url',
            'relative_path'
       ]];
    }

    /**
     * @dataProvider getFunctions()
     */
    public function testThatExtensionContainsFunction($function)
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $extension = new Psr7UriExtension($request);

        $extensionFunction = array_map(
            function ($function) {
                return $function->getName();
            },
            $extension->getFunctions()
        );

        $this->assertContains($function, $extensionFunction);
    }

    public function testGenerateAbsoluteUrlThenPathIsMissing()
    {
        $path = null;
        $expected = 'http://localhost:80/test';

        $uri = $this->createMockUri('http', 'localhost', '/test', 80);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->any())
            ->method('getUri')
            ->willReturn($uri);

        $extension = new Psr7UriExtension($request);
        $absoluteUrl = $extension->generateAbsoluteUrl($path);

        $this->assertEquals($expected, $absoluteUrl);
    }

    public function testGenerateAbsoluteUrlThenPathIsNetwork()
    {
        $path = '//';
        $expected = '//';

        $uri = $this->createMockUri('http', 'localhost', '/test', 80);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->any())
            ->method('getUri')
            ->willReturn($uri);

        $extension = new Psr7UriExtension($request);
        $absoluteUrl = $extension->generateAbsoluteUrl($path);

        $this->assertEquals($expected, $absoluteUrl);
    }

    public function testGenerateAbsoluteUrlThenPathHasLeadingSlash()
    {
        $path = '/';
        $expected = 'http://localhost:80/test/';

        $uri = $this->createMockUri('http', 'localhost', '/test', 80);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->any())
            ->method('getUri')
            ->willReturn($uri);

        $extension = new Psr7UriExtension($request);
        $absoluteUrl = $extension->generateAbsoluteUrl($path);

        $this->assertEquals($expected, $absoluteUrl);
    }
}
