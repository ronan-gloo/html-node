<?php

namespace HtmlNode\Component;

use
	HtmlNode\Collection,
	HtmlNode\Util,
	HtmlNode\Selector,
	InvalidArgumentException
;

trait Attribute {
	
	/**
	 * Attributes keys
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected static $attributeKeys = [
		"style"	=> [], 	// css inline styles
		"class"	=> [],	// css classes
		"data"	=> [],	// html5 data based
		"aria"	=> []		// html5 aria based
	];
	
	/**
	 * Attributes store
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $attributes = [];
	
	/**
	 * Initialize attributes
	 * 
	 * @access public
	 * @return void
	 */
	public function attributes($attributes = [])
	{
		if (! is_array($attributes))
		{
			throw new InvalidArgumentException("Argument should be an array");
		}
		
		// Setup keys
		$this->attributes = new Collection\Attribute(static::$attributeKeys);
		
		$attributes and $this->attr($attributes);
	}
	
	/**
	 * Set / Get Attr.
	 * 
	 * @access protected
	 * @param mixed $name
	 * @param mixed $val (default: null)
	 * @return void
	 */
	public function attr($name = null, $val = null)
	{
		if (! $name)
		{
			return $this->attributes->get();
		}
		// Getter
		if (! is_array($name) and func_num_args() === 1)
		{
			return $this->attributes->find($name);
		}
		// Setter
		if (! is_array($name))
		{
			$name = [$name => $val];
		}
		
		foreach ($name as $key => $value)
		{
			switch($key)
			{
				case "style":
				$this->css($value);
				break;
				case "class":
				$this->attributes[$key] = $this->parseClass($value);
				break;
				case "data":
				case "aria":
				$this->$key($value);
				break;
				default:
				$this->attributes[$key] = ($value === true) ? $key : $value;
				break;
			}
		}
		return $this;
	}
		
	/**
	 * Delete an attribute.
	 * 
	 * @access public
	 * @param mixed $name
	 * @return void
	 */
	public function addAttrIf($attr, $left, $right = null)
	{
		$numa = func_num_args();
		
		if (($left and $numa === 2) xor ($numa === 3 and ($left === $right)))
		{
			$this->attr($attr, $left);
		}
		return $this;
	}

	/**
	 * Delete an attribute.
	 * 
	 * @access public
	 * @param mixed $name
	 * @return void
	 */
	public function removeAttr($name)
	{
		return $this->attributes->delete($name);
	}
		
	/**
	 * new Class / classes.
	 * @access public
	 * @return void
	 */
	public function addClass($data)
	{
		$classes =& $this->attributes->eq("class");
		
		foreach ($this->parseClass($data) as $class)
		{
			if (! in_array($class, $classes))
			{
				$classes[] = $class;
			}
		}
		return $this;
	}
	
	/**
	 * @access public
	 * @param mixed $class
	 * @param mixed $bool
	 * @return $this
	 */
	public function addClassIf($class, $left, $right = null)
	{
		$numa = func_num_args();
		
		if (($left and $numa === 2) xor ($numa === 3 and ($left === $right)))
		{
			$this->addClass($class);
		}
		return $this;
	}
	
	/**
	 * Remove class / classes.
	 * 
	 * @access public
	 * @return void
	 */
	public function removeClass($data)
	{
		$classes =& $this->attributes->eq("class");
		
		foreach ($this->parseClass($data) as $class)
		{
			if (in_array($class, $classes))
			{
				unset($classes[$class]);
			}
		}
		return $this;
	}
	
	/**
	 * @access public
	 * @param mixed $class
	 * @param mixed $bool
	 * @return $this
	 */
	public function removeClassIf($class, $left, $right = null)
	{
		$numa = func_num_args();
		
		if (($left and $numa === 2) xor ($numa === 3 and ($left === $right)))
		{
			$this->removeClass($class);
		}
		return $this;
	}

	/**
	 * Shortcut to assign data-* attributes.
	 * 
	 * @access public
	 * @return void
	 */
	public function data($key = null, $val = null)
	{
		// We need to keep the args number, in order to check for Set / Get methods
		return call_user_func_array([$this, "recursiveAttr"], array_merge(["data"], func_get_args()));
	}
	
	/**
	 * Shortcut to assign aria-* attributes.
	 * 
	 * @access public
	 * @return void
	 */
	public function aria($key = null, $val = null)
	{
		return call_user_func_array([$this, "recursiveAttr"], array_merge(["aria"], func_get_args()));
	}
	
	/**
	 * Set / get Récursive.
	 * 
	 * @access protected
	 * @param mixed $ns
	 * @param mixed $key
	 * @param mixed $val
	 * @return void
	 */
	protected function recursiveAttr($ns, $key, $val = null)
	{
		if (! is_array($key) and func_num_args() === 2)
		{
			return $this->attributes->findRecursive($ns.".".$key);
		}
		
		! is_array($key)
			? $this->attributes->setRecursive($ns.".".$key, ($val === true) ? $key : $val)
			: $this->attributes->setRecursive($ns, $key);
		
		return $this;
	}
	
	/**
	 * Check against tagname, id, class or pseudo
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	public function is($attr)
	{
		$is = Selector\Selector::pseudo($this, $attr);
		
		return (bool)$is;
	}

	/**
	 * Check against tagname or attribute.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	public function not($attr)
	{
		return ! $this->is($attr);
	}
	
	/**
	 * @access public
	 * @param mixed $class
	 * @return void
	 */
	public function hasClass($class)
	{
		return in_array($class, $this->attributes["class"]);
	}
	
	/**
	 * parseCssClass function.
	 * 
	 * @access protected
	 * @param mixed $str
	 * @return void
	 */
	protected function parseClass($data)
	{
		$splitted = [];
		
		! is_array($data) and $data = [$data];
		
		foreach ($data as $val)
		{
			$splitted = array_merge($splitted, preg_split('/[\s,]+/', $val));
		}
		
		foreach ($splitted as $key => $val)
		{
			if (! is_string($val))
			{
				unset($splitted[$key]);
			}
		}
		
		return $splitted;
	}

}