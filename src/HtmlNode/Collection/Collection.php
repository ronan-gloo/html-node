<?php

namespace HtmlNode\Collection;

use
	Closure,
	HtmlNode\Util;
;

class Collection implements CollectionInterface {
	
	use CollectionAccess;
	
	/**
	 * Storage
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $items;
	
	/**
	 * @param array $items (default: [])
	 */
	public function __construct($items = [])
	{
		$this->items = $items;
	}
		
	/**
	 * @access public
	 */
	public function first()
	{
		return reset($this->items);
	}
	
	/**
	 * @access public
	 */
	public function last()
	{
		return end($this->items);
	}
	
	/**
	 * @access public
	 */
	public function length()
	{
		return count($this->items);
	}
	
	/**
	 * @access public
	 * @param mixed $value
	 */
	public function has($item)
	{
		return in_array($item, $this->items, true);
	}
	
	/**
	 * @access public
	 * @param mixed $key
	 */
	public function own($key)
	{
		return array_key_exists($key, $this->items);
	}
	
	/**
	 * @access public
	 * @param mixed $key
	 */
	public function search($item)
	{
		if ($key = array_search($item, $this->items, true))
		{
			return $this->items[$key];
		}
		return null;
	}
	
	/**
	 * @access public
	 */
	public function get()
	{
		return $this->items;
	}
	
	/**
	 * @access public
	 */
	public function &eq($index)
	{
		if ($exists = array_key_exists($index, $this->items))
		{
			return $this->items[$index];
		}
		$exists = null;
		return $exists;
	}
	
	/**
	 * @access public
	 * @param mixed $item
	 */
	public function indexOf($item)
	{
		return array_search($item, $this->items, true);
	}
	
	/**
	 * @access public
	 * @param mixed $key
	 */
	public function find($key)
	{
		if (array_key_exists($key, $this->items))
		{
			return $this->items[$key];
		}
	}
	
	
	/**
	 * @param mixed $key
	 * @param mixed $val
	 */
	public function set($key, $val = null)
	{		
		return !! $this->items[$key] = $val;
	}
	
	/**
	 * @access public
	 * @param mixed $items
	 */
	public function replaceWith($items)
	{
		$this->items = $items;
		return $this;
	}
	
	/**
	 * @access public
	 */
	public function copy()
	{
		return new static(unserialize(serialize($this->items)));
	}

	/**
	 * @access public
	 */
	public function prepend($data)
	{
		return !! array_unshift($this->items, $data);
	}
	
	/**
	 * @access public
	 */
	public function append($data)
	{
		return !! $this->items[] = $data;
	}
	
	/**
	 * @access public
	 * @param mixed $element
	 * @param mixed $new
	 */
	public function insertBefore($element, $new)
	{
		if (($pos = array_keys($this->items, $element, true)) !== []) 
		{
			$pos = reset($pos);
			return $this->insert($new, $pos);
		}
		return false;
	}
	
	/**
	 * @access public
	 * @param mixed $element
	 * @param mixed $new
	 */
	public function insertAfter($element, $new)
	{
		if ($pos = array_keys($this->items, $element, true))
		{
			return $this->insert($new, current($pos) + 1);
		}
		return false;
	}
	
	/**
	 * @access public
	 * @param mixed $new
	 * @param mixed $pos
	 */
	public function insert($new, $pos)
	{
		! is_array($new) and $new = [$new];

		$this->items = array_merge(
			array_slice($this->items, 0, $pos),
			$new,
			array_slice($this->items, $pos, null)
		);
		
		return true;
	}
	
	/**
	 * @access public
	 * @param mixed $key
	 */
	public function delete($key)
	{
		if (isset($this->items[$key]))
		{
			unset($this->items[$key]);
			return true;
		}
		return false;
	}

	/**
	 * @access public
	 * @param mixed $value
	 */
	public function remove($item)
	{
		$key = array_search($item, $this->items, true);
		
		if ($out = ($key !== false))
		{
			unset($this->items[$key]);
		}
		return $out;
	}
	
	/**
	 * @access public
	 * @param mixed $offset
	 * @param mixed $length (default: null)
	 */
	public function slice($offset, $length = null)
  {
	  $this->items = array_slice($this->items, $offset, $length);
	  
	  return $this;
  }

	/**
	 * @access public
	 */
	public function clear()
	{
		return ! $this->items = [];
	}
	
	/**
	 * @access public
	 * @param Closure $c
	 */
	public function each(Closure $c)
	{
		foreach ($this->items as $key => &$val)
		{
			if ($c($key, $val) === false) break;
		}
		return $this;
	}
	
	/**
	 * @access public
	 * @param Closure $c
	 */
	public function filter($c = "")
	{
		if ($c instanceof Closure)
		{
			$this->items = array_filter($this->items, $c);
		}
		else
		{
			$this->items = array_filter($this->items);
		}
		return $this;
	}
	
}