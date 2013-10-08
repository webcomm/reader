<?php

namespace Webcomm\Carousel\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Webcomm\Carousel\Carousel;

class CarouselTest extends PHPUnit_Framework_TestCase
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

    public function testGettingFinder()
    {
        $carousel = new Carousel($finder = m::mock('Webcomm\Carousel\Finder'));
        $this->assertEquals($finder, $carousel->getFinder());
    }

    public function testGettingBasicItems()
    {
        $carousel = new Carousel($finder = m::mock('Webcomm\Carousel\Finder'));

        $finder->shouldReceive('findFiles')->with(null)->once()->andReturn(array('foo', 'bar'));
        $items = $carousel->getItems();
        $this->assertCount(2, $items);
        $this->assertEquals('foo', $items[0]->getFile());
        $this->assertEquals('bar', $items[1]->getFile());

        // Test cache against Mockery expectations
        $carousel->getItems();
    }

    public function testGettingRelativeItems()
    {
        $carousel = new Carousel($finder = m::mock('Webcomm\Carousel\Finder'));

        $finder->shouldReceive('findFiles')->with('relative')->once()->andReturn(array('foo', 'bar'));
        $items = $carousel->getItems('relative');
        $this->assertCount(2, $items);
        $this->assertEquals('foo', $items[0]->getFile());
        $this->assertEquals('bar', $items[1]->getFile());

        // Test cache against Mockery expectations
        $carousel->getItems('relative');
    }
}
