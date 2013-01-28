<?php

namespace HtmlNode\Collection;

trait CollectionAccess {
	
	public function offsetExists($offset)
	{
	   return $this->own($offset);
	}
	
	public function offsetGet($offset)
	{
	   return $this->find($offset);
	}
	
	public function offsetSet($offset, $value)
	{
    return $this->items[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
	  return $this->delete($offset);
	}
	
	public function getIterator()
	{
		return new \ArrayIterator($this->items);
	}
}