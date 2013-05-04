<?php

namespace HtmlNode\Collection;

use
	Closure,
	HtmlNode\Util;
;

/**
 * Class Collection
 * @package HtmlNode\Collection
 */
class Collection implements CollectionInterface {
	
	use CollectionAccess;

    /**
     * @var array
     */
    protected $items;

    /**
     * @param array $items
     */
    public function __construct($items = [])
	{
		$this->items = $items;
	}

    /**
     * @return mixed
     */
    public function first()
	{
		return reset($this->items);
	}

    /**
     * @return mixed
     */
    public function last()
	{
		return end($this->items);
	}

    /**
     * @return int
     */
    public function length()
	{
		return count($this->items);
	}

    /**
     * @param $item
     * @return bool
     */
    public function has($item)
	{
		return in_array($item, $this->items, true);
	}

    /**
     * @param mixed $key
     * @return bool
     */
    public function own($key)
	{
		return array_key_exists($key, $this->items);
	}

    /**
     * @param mixed $item
     * @return null
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
     * @return array
     */
    public function get()
	{
		return $this->items;
	}

    /**
     * @param $index
     * @return null
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
     * @param $item
     * @return mixed
     */
    public function indexOf($item)
	{
		return array_search($item, $this->items, true);
	}

    /**
     * @param $key
     * @return mixed
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
     * @param null $val
     * @return mixed
     */
    public function set($key, $val = null)
	{		
		return !! ($this->items[$key] = $val);
	}

    /**
     * @param mixed $items
     * @return $this
     */
    public function exchange($items)
	{
		$this->items = $items;
		return $this;
	}

    /**
     * @return static
     */
    public function copy()
	{
		return new static(unserialize(serialize($this->items)));
	}

    /**
     * @param $data
     * @return bool
     */
    public function prepend($data)
	{
		return !! array_unshift($this->items, $data);
	}

    /**
     * @param $data
     * @return mixed
     */
    public function append($data)
	{
		return !! ($this->items[] = $data);
	}

    /**
     * @param mixed $element
     * @param mixed $new
     * @return bool
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
     * @param mixed $element
     * @param mixed $new
     * @return bool
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
     * @param mixed $new
     * @param $pos
     * @return bool
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
     * @param mixed $key
     * @return bool
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
     * @param $item
     * @return bool
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
     * @param $offset
     * @param null $length
     * @return $this
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
     * @param callable $c
     * @return $this
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
     * @param string $c
     * @return $this
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