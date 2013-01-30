<?php

namespace HtmlNode\Collection;

/**
 * @extends Collection
 */
class Attribute extends Collection {
	
	/**
	 * @access public
	 * @param mixed $key
	 * @return void
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
	 * @param mixed $key
	 * @param mixed $val
	 */
	public function setRecursive($key, $val = null)
	{		
		if(! is_array($key))
		{
			$key = [$key => $val];
		}
		// Batch set recursivelly items
		$items =& $this->items;
		
		foreach ($key as $name => $value)
		{
			$keys = explode('.', $name);
			
			while (count($keys) > 1)
			{
				$k = array_shift($keys);

				if ( ! isset($items[$k]) or ! is_array($items[$k]))
				{
					$items[$k] = [];
				}
				$items =& $items[$k];
			}
			$items[array_shift($keys)] = $value;
		}
		return count($key);
	}
	
	/**
	 * @access public
	 * @return void
	 */
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
	}
}