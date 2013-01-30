<?php

namespace HtmlNode\Tests;

use HtmlNode\Node;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
	/** @dataProvider getCssTests */
	public function testProperties($d)
	{
		$this->assertClassHasAttribute("config", get_class($d));
	}
	
	/** @dataProvider getCssTests */
	public function testConfigPair($d, $key, $val)
	{
		$obj = $d->config($key, $val);
		
		// Getter
		$this->assertSame(
			$obj->config($key),
			$val
		);
	}

	/** @dataProvider getCssTests */
	public function testConfigArray($d, $key, $val, $array)
	{
		$obj = $d->config($array);
		
		// Num set
		$this->assertContains(
			$obj->config(key($array)),
			$array
		);
	}
	
  public function getCssTests()
  {
  	foreach(range(1, 5) as $val)
  	{
	  	$array["key$val"] = "val$val";
  	}
    
    return [[Node::make("div"), "reload", false, $array]];
  }
}

