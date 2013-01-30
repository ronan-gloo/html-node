<?php
namespace HtmlNode\Tests;

use HtmlNode\Node;

class CssTest extends \PHPUnit_Framework_TestCase
{
	/** @dataProvider getCssTests */
	public function testProperties($d)
	{
		$this->assertClassHasStaticAttribute("cssNumber", get_class($d));
	}
	
	/** @dataProvider getCssTests */
	public function testCssPair($d, $key, $val)
	{
		$obj = $d->css($key, $val);
		
		// Getter
		$this->assertSame(
			$obj->css($key),
			$val
		);
	}

	/** @dataProvider getCssTests */
	public function testCssArray($d, $color, $cval, $width, $wval)
	{
		$obj = $d->css([$color => $cval, $width => $wval]);
		
		// Num set
		$this->assertSame(
			$obj->css($width),
			$wval."px"
		);

		$this->assertSame(
			$obj->css($color),
			$cval
		);
	}

	/** @dataProvider getCssTests */
	public function testCssNumber($d, $color, $cval, $width, $wval)
	{
		$obj = $d->css([$color => $cval, $width => $wval]);
		
		// Num set
		$this->assertSame(
			$obj->css($width),
			$wval."px"
		);

		$this->assertSame(
			$obj->css($color),
			$cval
		);
	}
	
  public function getCssTests()
  {
    return [[Node::make("div"), "color", "red", "width", 20]];
  }
}

