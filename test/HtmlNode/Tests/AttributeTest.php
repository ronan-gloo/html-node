<?php

namespace HtmlNode\Tests;

use HtmlNode\Node;

class AttributeTest extends \PHPUnit_Framework_TestCase
{
	/** @dataProvider getAttributeTests */
	public function testProperties($d)
	{
		$this->assertClassHasAttribute("attributes", get_class($d));
		$this->assertClassHasAttribute("attributeKeys", get_class($d));
	}
	
	/** @dataProvider getAttributeTests */
	public function testAttributes($d, $attrs)
	{
		try {
			$d->attributes("invalid");
		}
		catch (\Exception $e) {
			$this->assertInstanceOf("InvalidArgumentException", $e);
		}
		
		$this->assertInstanceOf(get_class($d), $d->attributes($attrs));
		
		$this->assertInstanceOf("HtmlNode\Collection\Attribute", $d->attributes());
	}
	
	/**
	 * @dataProvider getAttributeTests
	 */
	public function testAttr($d, $attrs)
	{
		$this->assertInstanceOf(get_class($d), $d->attr($attrs));
		$this->assertSame("email", $d->attr("name"));
	}

	/**
	 * @dataProvider getAttributeTests
	 */
	public function testAddAttrIf($d, $attrs)
	{
		// test nothing set
		$this->assertInstanceOf(get_class($d), $d->addAttrIf(["value2" => "v1"], false));
		$this->assertNull($d->attr("value1"));

		// test nothing set
		$this->assertInstanceOf(get_class($d), $d->addAttrIf(["value2" => "v2"], false, true));
		$this->assertNull($d->attr("value2"));

		// test is set
		$this->assertInstanceOf(get_class($d), $d->addAttrIf(["value3" => "v3"], true));
		$this->assertSame("v3", $d->attr("value3"));

		// test is set
		$this->assertInstanceOf(get_class($d), $d->addAttrIf(["value4" => "v4"], true, true));
		$this->assertSame("v4", $d->attr("value4"));
	}

	/**
	 * @dataProvider getAttributeTests
	 */
	public function testRemoveAttr($d, $attrs)
	{
		$this->assertInstanceOf(get_class($d), $d->removeAttr("foo"));
		$this->assertNull($d->attr($attrs)->removeAttr("name")->attr("name"));
	}

	/**
	 * @dataProvider getClassesTests
	 */
	public function testAddClass($d, $classes)
	{
		$this->assertInstanceOf(get_class($d), $d->addClass("foo"));
		
		$d->addClass($classes);
		
		if (is_string($classes)) $classes = explode(" ", $classes);
		
		foreach ($classes as $class)
		{
			$this->assertContains($class, $d->attr("class"));
		}
	}

	/**
	 * @dataProvider getClassesTests
	 */
	public function testHasClass($d, $classes)
	{
		$d->addClass($classes);
		
		if (is_string($classes)) $classes = explode(" ", $classes);
		
		foreach ($classes as $class)
		{
			$this->assertTrue($d->hasClass($class));
		}
	}

	/**
	 * @dataProvider getClassesTests
	 */
	public function testRemoveClass($d, $classes)
	{
		$this->assertInstanceOf(get_class($d), $d->removeClass("foo"));
		
		$d->addClass($classes);
		$d->removeClass($classes);
		$this->assertEmpty($d->attr("class"));
	}

	/**
	 * @dataProvider getDataTests
	 */
	public function testData($d, $key, $val, $array)
	{
		$this->assertInstanceOf(get_class($d), $d->data($key, $val));
		$this->assertInstanceOf(get_class($d), $d->data("foo", $array));
		
		$this->assertSame($val, $d->data($key));
		$this->assertSame($array, $d->data("foo"));
		
		$this->assertSame("foo3", $d->data("foo.foo2.bar2"));
		
		$this->assertContains($array, $d->data());
	}

	/**
	 * @dataProvider getAttributeTests
	 */
	public function testIs($d, $attrs)
	{
		$d->attributes($attrs);
		
		$this->assertTrue($d->is("[name]"));
		$this->assertTrue($d->is("[name=\"email\"]"));
		$this->assertTrue($d->is(".test"));
		$this->assertTrue($d->is("#foo"));
		$this->assertTrue($d->is(":enabled"));
		$this->assertTrue($d->is("div"));
		
		$this->assertFalse($d->is("[name=\"email"));
		$this->assertFalse($d->is("[foo]"));
		$this->assertFalse($d->is("[name=\"foo\"]"));
		$this->assertFalse($d->is(".bar"));
		$this->assertFalse($d->is("#bar"));
		$this->assertFalse($d->is(":bar"));
		$this->assertFalse($d->is("input"));
	}
	
	
  public function getAttributeTests()
  {
    return [
    	[new Node("div"),
    	["class" => "test", "data" => "val", "name" => "email", "id" => "foo", "enabled" => "enabled"],
    ]];
  }
  public function getClassesTests()
  {
    return [[new Node("div"), ["foo", "bar"]], [new Node("div", "text"), "foo bar"]];
  }
  public function getDataTests()
  {
    return [[new Node("div"), "foo1", "bar1", ["foo2" => ["bar2" => "foo3"]]]];
  }
}

