<?php

namespace HtmlNode\Collection;


interface CollectionInterface extends \ArrayAccess, \IteratorAggregate, \Countable {
	
	/**
	 * Retrieve the first item.
	 * 
	 * @access public
	 * @return Mixed
	 */
	public function first();
	
	/**
	 * Retrieve the last item.
	 * 
	 * @access public
	 * @return Mixed
	 */
	public function last();
	
	/**
	 * Itms count.
	 * 
	 * @access public
	 * @return Int
	 */
	public function length();
	
	/**
	 * Returns all or a specific item
	 * 
	 * @access public
	 * @return Array
	 */
	public function get();
	
	/**
	 * Alias to offsetGet()
	 *
	 * @access public
	 * @return void
	 */
	public function eq($index);

	/**
	 * Get the item index
     * @param integer $index
	 * @return integer|-1 if item doesnt exists
	 */
	public function indexOf($index);

	/**
	 * Set a new item.
	 * If $key is an array or Traversable, items of the array
	 * will be set recursively
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $val (default: null)
	 * @return $this
	 */
	public function set($key, $val = null);
	
	/**
	 * Replace current collection items.
	 * 
	 * @access public
	 * @param mixed $items
	 * @return $this
	 */
	public function exchange($items);

	/**
	 * Return a new copy of the current instance,
	 * with deep clone of items
	 * 
	 * @access public
	 * @param mixed $items
	 * @return $this
	 */
	public function copy();
	
	/**
	 * The methods push each args the the items
	 * 
	 * @access public
	 * @param mixed $key
	 * @return Int pushed
	 */
	public function append($data);
	
	/**
	 * The methods unshift args the the items
	 * 
	 * @access public
	 * @param mixed $key
	 * @return Int pushed
	 */
	public function prepend($data);
	
	/**
	 * Insert $new before $element.
	 * Supports associative array, and $new as array
	 * 
	 * @access public
	 * @param mixed $element
	 * @param mixed $new
	 * @return true on succes, false otherwise
	 */
	public function insertBefore($element, $new);
	
	/**
	 * Insert $new after $element.
	 * Supports associative array, and $new as array
	 * 
	 * @access public
	 * @param mixed $element
	 * @param mixed $new
	 * @return true on succes, false otherwise
	 */
	public function insertAfter($element, $new);
	
	/**
	 * Insert $new at pos.
	 * 
	 * @access public
	 * @param mixed $element
	 * @param mixed $new
	 * @return true on succes, false otherwise
	 */
	public function insert($new, $pos);
	
	/**
	 * Delete an item by it s key.
	 * 
	 * @access public
	 * @param mixed $key
	 * @return Bool, true if succeded, false otherwise
	 */
	public function delete($key);

	/**
	 * Delete an item by it s value.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return Bool, true if succeded, false otherwise
	 */
	public function remove($item);
	
	/**
	 * Remove all items from the collection.
	 * 
	 * @access public
	 * @return void
	 */
	public function clear();
	
	/**
	 * Check if $value is present in items.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return Bool, true if value is found, false otherwise
	 */
	public function has($item);
	
	/**
	 * Check if $key is a part of items
	 * 
	 * @access public
	 * @param mixed $key
	 * @return Bool, true if value is found, false otherwise
	 */
	public function own($key);
	
	/**
	 * Returns the index position of $item.
	 * 
	 * @access public
	 * @param mixed $item
	 * @return Int
	 */
	public function search($item);
	
	/**
	 * Walk throught items. Callback accepts
	 * $key as first arg and $value as second arg.
	 * $value can be use by reference inside the closure.
	 * If the callback returns false, we break the loop.
	 * 
	 * @access public
	 * @param Closure $c
	 * @return the last $key traversed
	 */
	public function each(\Closure $c);
	
	/**
	 * Apply the filter function to the colllection.
	 * 
	 * 
	 * @access public
	 * @param Closure $c
	 * @return void
	 */
	public function filter($c = "");
	
}