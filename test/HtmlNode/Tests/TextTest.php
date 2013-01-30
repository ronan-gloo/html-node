<?php
namespace HtmlNode\Tests;

use HtmlNode\Dependency;

class TextTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * @dataProvider getTextdata
	 */
	public function testProperties($text)
	{
		$this->assertClassHasAttribute("text", get_class($text));
		$this->assertClassHasAttribute("position", get_class($text));
	}

	/**
	 * @dataProvider getTextdata
	 */
	public function testToString($text)
	{
		$this->assertSame("foo", strval($text));
	}

	/**
	 * @dataProvider getTextdata
	 */
	public function testSet($text)
	{
		$this->assertSame("bar", strval($text->set("bar")));
		
		try {
			$text->set([]);
			$text->set(new stdClass);
		}
		catch (\Exception $e) {
			$this->assertInstanceOf("InvalidArgumentException", $e);
		}
	}

	/**
	 * @dataProvider getTextdata
	 */
	public function testPosition($text)
	{
		$this->assertEquals(0, $text->position());
		$this->assertEquals(1, $text->position(1)->position());
		$this->assertEquals(0, $text->position("string")->position());
	}

	/**
	 * @dataProvider getTextdata
	 */
	public function testLength($text)
	{
		$this->assertEquals(strlen($text->get()), $text->length());
	}

	/**
	 * @dataProvider getTextdata
	 */
	public function testContains($text)
	{
		$text->set("Hello World 1");
		
		$this->assertTrue($text->contains("World"));
		
		$this->assertFalse($text->contains("Foo"));
		// text case sensitive
		$this->assertFalse($text->contains("world", true));
		// test type ksensitive
		$this->assertFalse($text->contains(1, false, true));
	}

	/**
	 * @dataProvider getTextdata
	 * @expectedException Exception
	 */
	public function testContainsEmpty($text)
	{
		$text->contains();
	}

	/**
	 * @dataProvider getTextdata
	 */
	public function testReplace($text)
	{
		$this->assertEquals("fee", $text->replace("o", "e"));
	}

	/**
	 * @dataProvider getTextdata
	 */
	public function testMatch($text)
	{
		$this->assertGreaterThan(0, $text->match("/[f]/"));
	}

	/**
	 * @dataProvider getTextdata
	 */
	public function testGet($text)
	{
		$this->assertSame("foo", $text->get());
	}
	
	public function getTextdata()
	{
		$text = new Dependency\Text("foo");
		
		return [[$text]];
	}
}

