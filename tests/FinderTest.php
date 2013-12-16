<?php

namespace Webcomm\Carousel\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Webcomm\Carousel\Finder;

class FinderTest extends PHPUnit_Framework_TestCase
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

    public function testBasicFinding()
    {
        $finder = new Finder($locationGenerator = m::mock('Webcomm\Carousel\Locations\GeneratorInterface'));

        $locationGenerator->shouldReceive('getPath')->once()->andReturn(__DIR__.'/stubs/finder/basic');

        $files = $finder->findFiles();
        $this->assertCount(4, $files);
        $expected = array(
            __DIR__.'/stubs/finder/basic/1.jpg',
            __DIR__.'/stubs/finder/basic/2.jpeg',
            __DIR__.'/stubs/finder/basic/3.png',
            __DIR__.'/stubs/finder/basic/10.gif',
        );
        $actual = array_keys($files);
        $this->assertEquals($expected[0], $actual[0]);
        $this->assertEquals($expected[1], $actual[1]);
        $this->assertEquals($expected[2], $actual[2]);
        $this->assertEquals($expected[3], $actual[3]);
    }

    public function testRelativeFinding()
    {
        $finder = new Finder($locationGenerator = m::mock('Webcomm\Carousel\Locations\GeneratorInterface'));

        $locationGenerator->shouldReceive('getPath')->once()->andReturn(__DIR__.'/stubs/finder/relative');

        $files = $finder->findFiles();
        $this->assertCount(0, $files);

        $locationGenerator->shouldReceive('getPath')->with('foo')->once()->andReturn(__DIR__.'/stubs/finder/relative/foo');

        $files = $finder->findFiles('foo');
        $this->assertCount(1, $files);
        $expected = array(
            __DIR__.'/stubs/finder/relative/foo/fred.gif',
        );
        $actual = array_keys($files);
        $this->assertEquals($expected[0], $actual[0]);

        $locationGenerator->shouldReceive('getPath')->with('bar')->once()->andReturn(__DIR__.'/stubs/finder/relative/bar');

        $files = $finder->findFiles('bar');
        $this->assertCount(2, $files);
        $expected = array(
            __DIR__.'/stubs/finder/relative/bar/baz.jpg',
            __DIR__.'/stubs/finder/relative/bar/quz.png',
        );
        $actual = array_keys($files);
        $this->assertEquals($expected[0], $actual[0]);
        $this->assertEquals($expected[1], $actual[1]);
    }

    public function testMarkdownMeta()
    {
        $finder = new Finder($locationGenerator = m::mock('Webcomm\Carousel\Locations\GeneratorInterface'));

        $meta = $finder->findMeta(__DIR__.'/stubs/finder/meta-markdown/foo.jpg');
        list($caption, $data) = $meta;

        $expected = <<<CAPTION
<p>This is my <strong>markdown</strong> caption!</p>

<h3>Woohoo!</h3>
CAPTION;

        $this->assertEquals($expected, $caption);

        $expected = array(
            'foo' => 'This is the foo attribute.',
            'slug' => 'this-is-the-slug',
        );
        $this->assertEquals($expected, $data);
    }

    public function testHtmlMeta()
    {
        $finder = new Finder($locationGenerator = m::mock('Webcomm\Carousel\Locations\GeneratorInterface'));

        $meta = $finder->findMeta(__DIR__.'/stubs/finder/meta-html/foo.jpg');
        list($caption, $data) = $meta;

        $expected = '<p>success</p>';
        $this->assertEquals($expected, $caption);

        $expected = array(
            'foo' => 'This is the foo attribute.',
            'slug' => 'this-is-the-slug',
        );
        $this->assertEquals($expected, $data);
    }

    public function testTextMeta()
    {
        $finder = new Finder($locationGenerator = m::mock('Webcomm\Carousel\Locations\GeneratorInterface'));

        $meta = $finder->findMeta(__DIR__.'/stubs/finder/meta-text/foo.jpg');
        list($caption, $data) = $meta;

        $expected = '&lt;p&gt;success&lt;/p&gt;';
        $this->assertEquals($expected, $caption);

        $expected = array(
            'foo' => 'This is the foo attribute.',
            'slug' => 'this-is-the-slug',
        );
        $this->assertEquals($expected, $data);
    }

    public function testMetaWithNoCaption()
    {
        $finder = new Finder($locationGenerator = m::mock('Webcomm\Carousel\Locations\GeneratorInterface'));

        $meta = $finder->findMeta(__DIR__.'/stubs/finder/meta-no-caption/foo.jpg');
        list($caption, $data) = $meta;

        $this->assertNull($caption);
    }
}
