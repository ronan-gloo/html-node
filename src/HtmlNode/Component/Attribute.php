<?php

namespace HtmlNode\Component;

use
	HtmlNode\Collection\Attribute as Collection,
	HtmlNode\Util,
	HtmlNode\Selector,
	InvalidArgumentException
;

trait Attribute {
		
	/**
	 * Attributes store
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $attributes = [];
	
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
				case Collection::KEY_STYLE:
				$this->css($value);
				break;
				case Collection::KEY_CLASS:
				$this->attributes[$key] = $this->parseClass($value);
				break;
				case Collection::KEY_DATA:
				case Collection::KEY_ARIA:
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
	public function removeAttr($attrs)
	{
		! is_array($attrs) and $attrs = [$attrs];

		foreach ($attrs as $name)
		{
			$this->attributes->delete($name);
		}
		return $this;
	}
		
	/**
	 * new Class / classes.
	 * @access public
	 * @return void
	 */
	public function addClass($data)
	{
		$classes =& $this->attributes->eq(Collection::KEY_CLASS);
		
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
		$classes =& $this->attributes->eq(Collection::KEY_CLASS);

		foreach ($this->parseClass($data) as $key => $class)
		{
			$index = array_search($class, $classes);
			
			if ($index !== false)
			{
				unset($classes[$index]);
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
	 * Exchange $old with $new.
	 * If old doesn't exists, add new anyway.
	 * 
	 * @access public
	 * @param mixed $old
	 * @param mixed $new
	 * @return $this
	 */
	public function switchClass($old, $new)
	{
		return $this->removeClass($old)->addClass($new);
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
		return call_user_func_array(
			[$this, "recursiveAttr"],
			array_merge([Collection::KEY_DATA], func_get_args())
		);
	}
	
	/**
	 * Shortcut to assign aria-* attributes.
	 * 
	 * @access public
	 * @return void
	 */
	public function aria($key = null, $val = null)
	{
		return call_user_func_array(
			[$this, "recursiveAttr"],
			array_merge([Collection::KEY_ARIA], func_get_args())
		);
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
	protected function recursiveAttr($ns, $key = null, $val = null)
	{
		if (! $key)
		{
			return $this->attributes[$ns];
		}
		
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
		return Selector\Selector::pseudo($this, $attr);
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
		return ! Selector\Selector::pseudo($this, $attr);
	}
	
	/**
	 * @access public
	 * @param mixed $class
	 * @return void
	 */
	public function hasClass($class)
	{
		return in_array($class, $this->attributes[Collection::KEY_CLASS]);
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