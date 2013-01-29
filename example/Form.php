<?php

use \HtmlNode\Node, HtmlNode\Extra;

/**
* An example of Class which extends HtmlNode\Node
*/
class Form extends HtmlNode\Node {
	
	// import val() and input()  method from FormInput trait
	use Extra\FormInput;
	
	/**
	 * Experimental support for html5 input type + classics
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected static $inputs = [
		"color",
		"date",
		"datetime",
		"email",
		"month",
		"number",
		"range",
		"search",
		"tel",
		"time",
		"url",
		"week",
		"button",
		"hidden",
		"password",
		"radio",
		"file",
		"reset",
		"submit"
	];
	
	/**
	 * @access public
	 * @param mixed $method
	 * @param mixed $args
	 * @return void
	 */
	public static function __callStatic($method, $args)
	{
		// Redirect calls to the input method...
		if (in_array($method, static::$inputs))
		{
			$args[2]["type"] = $method;
			return call_user_func_array(["self", "input"], $args);
		}
		return parent::__callStatic($method, $args);
	}
	
	/**
	 * textarea function.
	 */
	public static function textarea($name, $value = null, $attrs)
	{
		return static::make("textarea", $value, $attrs);
	}
	
	/**
	 * @access public
	 */
	public static function label($text, $attrs = [])
	{
		return static::make("label", $text, $attrs);
	}
	
	/**
	 * @access public
	 */
	public static function checkbox($name, $checked = false, $value = 1, $attrs = [])
	{
		$attrs["type"] 	= "checkbox";
		$attrs["value"]	= $value;
		
		return static::input($name, null, $attrs);
	}
	
	/**
	 * Checkbox wraped in a Label
	 * @access public
	 */
	public static function checkboxLabel($name, $text = "", $checked = false, $value = 1, $attrs = [])
	{
		$label		= static::label($text);
		$checkbox	= static::checkbox($name, $checked, $value, compact("name", "value", "checked") + $attrs);
		
		return $label->append($checkbox);
	}
	
	/**
	 * @access public
	 */
	public static function select($name, $values = null, $options = [], $attrs = [])
	{
		// force array type in order to be used with multiselects
		! is_array($values) and $values = [$values];
		
		// prepare variables
		$selected = ["selected" => true];
		$select		= static::make("select", null, $attrs);
		
		// Loop throught values to populate the select
		foreach ($options as $key => $val)
		{
			$select->append(static::option($key, $val)->addAttrIf($selected, in_array($key, $values)));
		}
		return $select;
	}
	
	/**
	 * @access public
	 */
	public static function option($key, $val, $attrs = [])
	{
		return static::make("option", $val, ["value" => $val] + $attrs);
	}
	
	/**
	 * @access public
	 */
	public static function multiselect($name, $values = [], $options = [], $attrs = [])
	{
		return static::select($name, $values, $options, ["multiple" => true] + $attrs);
	}
	
}
