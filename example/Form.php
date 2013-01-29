<?php

/**
* An example of Class which extends HtmlNode\Node
*/
class Form extends HtmlNode\Node {
	
	// import val() and input()  method from FormInput trait
	use HtmlNode\Extra\FormInput;
	
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
	public function __call($method, $args)
	{
		// Redirect calls to the input method...
		if (in_array($method, self::$inputs))
		{
			$args[2]["type"] = $method;
			$method = "input";
		}
		
		return call_user_func_array([$this, $method], $args);
	}
	
	/**
	 * textarea function.
	 */
	public function textarea($name, $value = null, $attrs)
	{
		return self::make("textarea", $value, $attrs);
	}
	
	/**
	 * @access public
	 */
	public function label($text, $attrs = [])
	{
		return self::make("label", $text, $attrs);
	}
	
	/**
	 * @access public
	 */
	public function checkbox($name, $checked = false, $value = 1, $attrs = [])
	{
		$attrs["type"] 	= "checkbox";
		$attrs["value"]	= $value;
		
		return $this->input($name, null, $attrs);
	}
	
	/**
	 * Checkbox wraped in a Label
	 * @access public
	 */
	public function checkboxLabel($name, $text = "", $checked = false, $value = 1, $attrs = [])
	{
		$label		= $this->label($text);
		$checkbox	= $this->checkbox($name, $checked, $value, compact("name", "value", "checked") + $attrs);
		
		return $label->append($checkbox);
	}
	
	/**
	 * @access public
	 */
	public function select($name, $values = null, $options = [], $attrs = [])
	{
		// force array type in order to be used with multiselects
		! is_array($values) and $values = [$values];
		
		// prepare variables
		$selected = ["selected" => true];
		$select		= self::make("select", null, $attrs);
		
		// Loop throught values to populate the select
		foreach ($options as $key => $val)
		{
			$select->append($this
				// Create option node...
				->option($key, $val)
				// ... and add selected state if values contains the current key
				->addAttrIf($selected, in_array($key, $values))
			);
		}
		return $select;
	}
	
	/**
	 * @access public
	 */
	public function option($key, $val, $attrs = [])
	{
		return self::make("option", $val, ["value" => $val] + $attrs);
	}
	
	/**
	 * @access public
	 */
	public function multiselect($name, $values = [], $options = [], $attrs = [])
	{
		return $this->select($name, $values, $options, ["multiple" => true] + $attrs);
	}
	
}
