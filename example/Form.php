<?php

use \HtmlNode\Node, HtmlNode\Library;

/**
* An example of Class which extends HtmlNode\Node
*/
class Form extends HtmlNode\Node {
	
	// import val() and input()  method from FormInput trait
	use Library\FormInput;
	
	/**
	 * Experimental support for html5 input type + classics
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected static $inputs = [
		"color","date","datetime","email","month","number","range",
		"search","tel","time","url","week","button","hidden",
		"radio","file","reset","submit","password"
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
			if (($count = count($args)) < 2)
			{
				$args = array_fill($count-1, 3-$count, null);
			}
			
			$args[2]["type"] = $method;
			return call_user_func_array(["self", "input"], $args);
		}
		// Check form registered nodes
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
	public static function select($name, $values = null, $opts = [], $attrs = [])
	{
		// force array type in order to be used with multiselects
		! is_array($values) and $values = [$values];
		
		// prepare variables
		$select	= static::make("select", null, $attrs);

		// Loop throught values to populate the select
		foreach ($opts as $val => $name)
		{
			$opt[] = static::option($val, $name)->addAttrIf(["selected" => true], in_array($val, $values));
		}

		return $select->html($opt);
	}
	
	/**
	 * @access public
	 */
	public static function option($key, $val, $attrs = [])
	{
		$attrs["value"] = $val;
		return static::make("option", $val, $attrs);
	}
	
	/**
	 * @access public
	 */
	public static function multiselect($name, $values = [], $options = [], $attrs = [])
	{
		$attrs["multiple"] = true;
		return static::select($name, $values, $options, $attrs);
	}
	
}
