<?php

namespace HtmlNode\Component;

trait Css {
	
	/**
	 * Do not automatically add 'px" on those numbers
	 * 
	 * @var mixed
	 * @access private
	 * @static
	 */
	private static $cssNumber = [
		'column-count',
		'fill-opacity',
		'font-height',
		'line-height',
		'opacity',
		'orphans',
		'widows',
		'z-index',
		'zoom',
	];
	
	/**
	 * Setup the css property.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $val (default: null)
	 * @return void
	 */
	public function css($key, $val = null)
	{
		if (! is_array($key)) {
			$key = [$key => $val];
		}
		foreach ($key as $name => &$value)
		{
			// not a good style element
			if (is_array($value) or ! trim($value)) {
				unset($key[$name]);
				continue;
			}
			
			// append "px" tyo numeric values if 
			if (is_numeric($value) and ! in_array($name, static::$cssNumber)) {
				$value = strval($value)."px";
			}
			$this->attributes->set("style.".str_replace(".", "-", $name), $value);
		}
		return $this;
	}

}