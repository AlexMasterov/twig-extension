<?php

namespace AlexMasterov\TwigExtension\Tests;

use AlexMasterov\TwigExtension\AbsoluteUrlGenerator;
use AlexMasterov\TwigExtension\Tests\UriMockTrait;
use PHPUnit_Framework_TestCase as TestCase;

class AbsoluteUrlGeneratorTest extends TestCase
{
    use UriMockTrait;

    public function testThenHostIsMissing()
    {
        $expected = '/';

        $uri = $this->createMockUri('http', '', '/', '');

        $generator = new AbsoluteUrlGenerator();
        $url = $generator($uri);

        $this->assertEquals($expected, $url);
    }

    public function testThenPortExists()
    {
        $expected = 'http://localhost:80';

        $uri = $this->createMockUri('http', 'localhost', '', 80);

        $generator = new AbsoluteUrlGenerator();
        $url = $generator($uri);

        $this->assertEquals($expected, $url);
    }

    public function testGenerateAbsoluteUrl()
    {
        $expected = 'http://localhost:80/test';

        $uri = $this->createMockUri('http', 'localhost', '/test', 80);

        $generator = new AbsoluteUrlGenerator();
        $url = $generator($uri);

        $this->assertEquals($expected, $url);
    }
}
