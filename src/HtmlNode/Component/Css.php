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
	protected static $cssNumber = [
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
		$tyles = $this->attributes->eq("style");
		
		if (! is_array($key))
		{
			if (func_num_args() == 1) return $tyles[$key];

			$key = [$key => $val];
		}

		foreach ($key as $name => &$value)
		{
			// not a good style element
			if (is_array($value) or ! trim($value))
			{
				unset($key[$name]);
				continue;
			}
			
			// append "px" tyo numeric values if 
			if (is_numeric($value) and ! in_array($name, static::$cssNumber))
			{
				$value = strval($value)."px";
			}
			// Add css rule
			$this->attributes->eq("style")[$name] = $value;
		}
		return $this;
	}

}