<?php

namespace HtmlNode\Collection;

/**
 * @extends Collection
 */
/**
 * Class Attribute
 * @package HtmlNode\Collection
 */
class Attribute extends Collection {

    const key_style	= "style";
    const key_class	= "class";
    const key_data	= "data";
    const key_aria	= "aria";

	/**
	 * @param array $items (default: [])
	 */
	public function __construct(array $items = [])
	{
		$this->items = $items + [
            self::key_style => [],
            self::key_class => [],
            self::key_data  => [],
            self::key_aria  => []
        ];
	}

	/**
	 * @access public
	 * @param mixed $key
	 * @return array
	 */
	public function findRecursive($key)
	{
		$array = $this->items;
		
		foreach (explode('.', $key) as $part)
		{
			if (! array_key_exists($part, $array))
			{
				return null;
			}
			$array = $array[$part];
		}
		return $array;
	}

    /**
     * @param $key
     * @param null $val
     * @return bool
     */
    public function setRecursive($key, $val = null)
	{		
		if(! is_array($key))
		{
			$key = [$key => $val];
		}

        $items =& $this->items;
		
		foreach ($key as $name => $value)
		{
			$keys = explode('.', $name);
			
			while (count($keys) > 1)
			{
				$k = array_shift($keys);

//				if ( ! isset($items[$k]) or ! is_array($items[$k]))
//				{
//					$items[$k] = [];
//				}
				$items =& $items[$k];
			}
			$items[array_shift($keys)] = $value;
		}
		return true;
	}
	
	/**
	 * @access public
	 * @return void
	public function extractRecursive($key)
	{
		$delete = function(&$items, $key) use (&$delete) {
			
			$parts = explode('.', $key);
	
			if (! is_array($items) or ! array_key_exists($parts[0], $items))
			{
				return false;
			}
			$ckey = array_shift($parts);
			
			if (! empty($items))
			{
				return $delete($items[$ckey], implode('.', $parts));
			}
			$out = $items[$ckey];
			unset($items[$ckey]);
			
			return $out;
		};
		return $delete($this->items, $key);
	}*/

}