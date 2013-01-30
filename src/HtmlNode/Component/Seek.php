<?php

namespace HtmlNode\Component;

use HtmlNode\Collection\Collection;

trait Seek {
	
	/**
	 * Find prev slibling.
	 * 
	 * @access public
	 * @return Node or void
	 */
	public function prev()
	{
		if ($parent = $this->parent)
		{
			return $parent->children()->eq($this->index()-1);
		}
		return null;
	}
	
	/**
	 * Find next sibling.
	 * 
	 * @access public
	 * @return Node or void
	 */
	public function next()
	{
		if ($parent = $this->parent)
		{
			return $parent->children()->eq($this->index()+1);
		}
		return null;
	}
	
	/**
	 * Get all nodes after from the next index.
	 * 
	 * @access public
	 * @return void
	 */
	public function nextAll($input = null)
	{
		if ($parent = $this->parent)
		{
			$nodes = clone $parent->children();
			$start = $nodes->indexOf($this) + 1;
			$nodes = $nodes->slice($start, $nodes->length() - $start);
			
			return $this->filterSeek($input, $nodes);
		}
		return new Collection;
	}

	/**
	 * Get all nodes after from the next index.
	 * 
	 * @access public
	 * @return void
	 */
	public function prevAll($input = null)
	{
		if ($parent = $this->parent)
		{
			$nodes = clone $parent->children();
			$nodes = $nodes->slice(0, $nodes->indexOf($this));
			
			return $this->filterSeek($input, $nodes);
		}
		return new Collection;
	}
	
	/**
	 * Get Siblings of $this in the parent collection
	 *  
	 * @access public
	 * @return void
	 */
	public function siblings($input = null)
	{
		if ($parent = $this->parent)
		{
			// First, clone the collection to not affect the original
			$nodes = clone $parent->children();
			
			// extract the current node from the copy
			$nodes->length() and $nodes->remove($this);
			
			return $this->filterSeek($input, $nodes);
		}
		return new Collection;
	}
	
	/**
	 * Filter results with $input
	 * 
	 * @access protected
	 * @param mixed $input
	 * @param mixed $nodes
	 * @return void
	 */
	protected function filterSeek($input, $nodes)
	{
		if ($nodes->length() and $input)
		{
			foreach ($nodes as $key => $node)
			{
				$node->not($input) and $nodes->remove($node);
			}
		}
		return $nodes;
	}
	
}