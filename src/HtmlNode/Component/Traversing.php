<?php

namespace HtmlNode\Component;

use
	HtmlNode,
	HtmlNode\Finder,
	HtmlNode\Collection,
	HtmlNode\Util,
	InvalidArgumentException
;

trait Traversing {
	
	/**
	 * Find a specific node from the collection,
	 * or find by tag name.
	 * 
	 * @access public
	 * @param mixed $node
	 * @return void
	 */
	public function find($input)
	{
		if (! is_string($input))
		{
			throw new InvalidArgumentException("You must provide a valid tagname");
		}
		return (new Util\Finder($this))->children($input);
	}

	/**
	 * Find the closest parent which match $input.
	 * 
	 * @access public
	 * @return void
	 */
	public function closest($input)
	{
		if (! is_string($input))
		{
			throw new InvalidArgumentException("You must provide a valid tagname");
		}
		return (new Util\Finder($this))->parents($input, false);
	}
	
	/**
	 * Get a single child, or the collection array
	 * if $child is null
	 * 
	 * @access public
	 * @param mixed $child (default: null)
	 * @return mixed
	 */
	public function children($input = null)
	{
		if ($input and is_string($input))
		{
			$childrens = new Collection\Collection;
		
			foreach ($this->children as $node)
			{
				$node->is($input) and $childrens->append($node);
			}
			return $childrens;
		}
		return $this->children;
	}
		
	/**
	 * Get the current parent node.
	 * 
	 * @access public
	 * @return void
	 */
	public function parent()
	{
		return $this->parent;
	}
	
	/**
	 * Get the node index.
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		return ! $this->parent ?: $this->parent->children()->indexOf($this);
	}

	/**
	 * Check if node has parent.
	 * 
	 * @access public
	 * @return void
	 */
	public function hasParent()
	{
		return (bool)$this->parent();
	}
	/**
	 * Check if current node is a child of $node
	 * 
	 * @access public
	 * @param Node $node
	 * @return Bool
	 */
	public function isChildOf(Node $node)
	{
		return $this->parent === $node;
	}
	
	/**
	 * Check if current node is the parent of $node
	 * 
	 * @access public
	 * @param Node $node
	 * @return void
	 */
	public function isParentOf(Node $node)
	{
		return $this->children->search($node) !== false;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function hasChildren()
	{
		return $this->children->legnth() > 0;
	}

	
}