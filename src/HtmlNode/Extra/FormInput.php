<?php

namespace HtmlNode\Extra;

trait FormInput {
		
	/**
	 * @access public
	 * @param mixed $name
	 * @param mixed $value (default: null)
	 * @param mixed $attrs (default: [])
	 * @return $this
	 */
	public function input($name, $value = null, array $attrs = [])
	{
		// force default input type if no exists
		! isset($attrs["type"]) and $attrs["type"] = "text";
		
		return self::make("input", null, compact("name", "value") + $attrs);
	}

	/**
	 * Set / Get value attribute for form elements
	 * 
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	public function val($value)
	{
		if (is_null($value))
		{
			return $this->attributes->eq("value");
		}
		
		$this->attributes->offsetSet("value", $value);

		return $this;
	}
	
}