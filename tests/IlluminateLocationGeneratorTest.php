<?php

namespace Webcomm\Carousel\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Webcomm\Carousel\Locations\IlluminateGenerator;

class IlluminateLocationGeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBasePathOutsidePublic()
    {
        $locationGenerator = new IlluminateGenerator($urlGenerator = m::mock('Illuminate\Routing\UrlGenerator'), __DIR__.'/..', __DIR__);
    }

    public function testPaths()
    {
        $locationGenerator = new IlluminateGenerator($urlGenerator = m::mock('Illuminate\Routing\UrlGenerator'), __DIR__.'/public/base', __DIR__.'/public');
        $this->assertEquals(__DIR__.'/public/base', $locationGenerator->getPath());
    }

    public function testPathUrl()
    {
        $locationGenerator = new IlluminateGenerator($urlGenerator = m::mock('Illuminate\Routing\UrlGenerator'), __DIR__, __DIR__);
        $urlGenerator->shouldReceive('asset')->with('foo/baz')->once()->andReturn($url = 'http://www.example.com');
        $this->assertEquals($url, $locationGenerator->getPathUrl(__DIR__.'/foo/bar/../baz'));
    }
}
