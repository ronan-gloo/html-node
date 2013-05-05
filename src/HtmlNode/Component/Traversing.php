<?php

namespace HtmlNode\Component;

use
	HtmlNode\NodeInterface,
	HtmlNode\Collection\Node as Collection,
	HtmlNode\Util\Finder,
	InvalidArgumentException
;

/**
 * Class Traversing
 * @package HtmlNode\Component
 */
trait Traversing {

    /**
     * Find a specific node from the collection,
     * or find by tag name.
     * @param $input
     * @return Collection
     * @throws \InvalidArgumentException
     */
    public function find($input)
	{
		if (! is_string($input))
		{
			throw new InvalidArgumentException("You must provide a valid tagname");
		}
		return (new Finder($this))->children($input);
	}

    /**
     * Find the closest parent which match $input.
     * @param $input
     * @return Collection
     * @throws \InvalidArgumentException
     */
    public function closest($input)
	{
		if (! is_string($input))
		{
			throw new InvalidArgumentException("You must provide a valid tagname");
		}
		return (new Finder($this))->parents($input, false);
	}

    /**
     * Get a single child, or the collection array
     * if $child is null.
     * @param null $input
     * @return Collection
     */
    public function children($input = null)
	{
		if ($input and is_string($input))
		{
			$childrens = new Collection;
		
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
	 * @return \HtmlNode\NodeInterface
	 */
	public function parent()
	{
		return $this->parent;
	}
	
	/**
	 * Get the node index.
	 * 
	 * @access public
	 * @return integer
	 */
	public function index()
	{
        return ! $this->parent ? 0 : $this->parent->children()->indexOf($this);
	}

	/**
	 * Check if node has parent.
	 * 
	 * @access public
	 * @return bool
	 */
	public function hasParent()
	{
		return (bool)$this->parent();
	}

    /**
     * Check if current node is a child of $node
     * @param NodeInterface $node
     * @return bool
     */
    public function isChildOf(NodeInterface $node)
	{
		return $this->parent === $node;
	}

    /**
     * Check if current node is the parent of $node
     * @param NodeInterface $node
     * @return bool
     */
    public function isParentOf(NodeInterface $node)
	{
		return $node->parent() == $this;
	}
	
	/**
	 * @access public
	 * @return bool
	 */
	public function hasChildren()
	{
		return $this->children->length() > 0;
	}
}