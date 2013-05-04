<?php

namespace HtmlNode\Component;

use
	HtmlNode\Collection\Attribute as Collection,
	HtmlNode\Util,
	HtmlNode\Selector
;

/**
 * Class Attribute
 * @package HtmlNode\Component
 */
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
	 * @return $this
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
		if (is_scalar($name))
		{
			$name = [$name => $val];
		}
		
		foreach ($name as $key => $value)
		{
			switch($key)
			{
				case Collection::key_style:
				$this->css($value);
				break;

                case Collection::key_class:
				$this->attributes[$key] = $this->parseClass($value);
				break;

                case Collection::key_data:
				case Collection::key_aria:
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
     * Delete an attribute
     * @param $attr
     * @param $left
     * @param null $right
     * @return $this
     */
    public function addAttrIf($attr, $left, $right = null)
	{
		$num = func_num_args();
		
		if (($left and $num === 2) xor ($num === 3 and ($left === $right)))
		{
			$this->attr($attr, $left);
		}
		return $this;
	}

    /**
     * @param $attrs
     * @return $this
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
     * @param $data
     * @return $this
     */
    public function addClass($data)
	{
		$classes =& $this->attributes->eq(Collection::key_class);
		
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
     * @param $class
     * @param $left
     * @param null $right
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
     * @param $data
     * @return $this
     */
    public function removeClass($data)
	{
		$classes =& $this->attributes->eq(Collection::key_class);

		foreach ($this->parseClass($data) as $class)
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
     * @param $class
     * @param $left
     * @param null $right
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
     * @param null $key
     * @param null $val
     * @return mixed
     */
    public function data($key = null, $val = null)
	{
		// We need to keep the args number, in order to check for Set / Get methods
		return call_user_func_array(
			[$this, "recursiveAttr"],
			array_merge([Collection::key_data], func_get_args())
		);
	}

    /**
     * @param null $key
     * @param null $val
     * @return mixed
     */
    public function aria($key = null, $val = null)
	{
		return call_user_func_array(
			[$this, "recursiveAttr"],
			array_merge([Collection::key_aria], func_get_args())
		);
	}

    /**
     * @param $ns
     * @param null $key
     * @param null $val
     * @return $this
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
     * @param $attr
     * @return bool
     */
    public function is($attr)
	{
		return Selector\Selector::pseudo($this, $attr);
	}


    /**
     * Check against tagname or attribute
     * @param $attr
     * @return bool
     */
    public function not($attr)
	{
		return ! Selector\Selector::pseudo($this, $attr);
	}

    /**
     * @param $class
     * @return bool
     */
    public function hasClass($class)
	{
		return in_array($class, $this->attributes[Collection::key_class]);
	}

    /**
     * @param $data
     * @return array
     */
    protected function parseClass($data)
	{
		$split = [];
		
		! is_array($data) and $data = [$data];
		
		foreach ($data as $val)
		{
            $split = array_merge($split, preg_split('/[\s,]+/', $val));
		}
		
		return $split;
	}

}