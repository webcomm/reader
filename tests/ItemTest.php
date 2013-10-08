<?php

namespace Webcomm\Carousel\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Webcomm\Carousel\Item;

class ItemTest extends PHPUnit_Framework_TestCase
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

    public function testGettingFile()
    {
        $item = new Item($carousel = m::mock('Webcomm\Carousel\Carousel'), __DIR__.'/file.jpg');
        $this->assertEquals(__DIR__.'/file.jpg', $item->getFile());
    }

    public function testGettingFileUrl()
    {
        $item = new Item($carousel = m::mock('Webcomm\Carousel\Carousel'), __DIR__.'/file.jpg');
        $carousel->shouldReceive('getFinder')->once()->andReturn($finder = m::mock('stdClass'));
        $finder->shouldReceive('getLocationGenerator')->once()->andReturn($locationGenerator = m::mock('stdClass'));
        $locationGenerator->shouldReceive('getPathUrl')->with(__DIR__.'/file.jpg')->once()->andReturn('success');
        $this->assertEquals('success', $item->getFileUrl());
    }

    public function testGettingCaption()
    {
        $item = new Item($carousel = m::mock('Webcomm\Carousel\Carousel'), __DIR__.'/file.jpg');
        $carousel->shouldReceive('getFinder')->once()->andReturn($finder = m::mock('stdClass'));
        $finder->shouldReceive('findAdditional')->once()->andReturn(array('caption', array('foo' => 'bar')));
        $this->assertEquals('caption', $item->getCaption());

        // Hit once more to test lazy load against Mockery expectations
        $item->getCaption();
    }

    public function testGettingData()
    {
        $item = new Item($carousel = m::mock('Webcomm\Carousel\Carousel'), __DIR__.'/file.jpg');
        $carousel->shouldReceive('getFinder')->once()->andReturn($finder = m::mock('stdClass'));
        $finder->shouldReceive('findAdditional')->once()->andReturn(array('caption', array('foo' => 'bar')));
        $this->assertEquals(array('foo' => 'bar'), $item->getData());

        // Hit once more to test lazy load against Mockery expectations
        $item->getData();
    }
}
