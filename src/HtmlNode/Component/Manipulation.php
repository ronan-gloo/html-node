<?php

namespace HtmlNode\Component;

use
	Closure,
	InvalidArgumentException,
	HtmlNode\Collection,
	HtmlNode\Node
;

trait Manipulation {
	
	/**
	 * Parent Node
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $parent;
	
	/**
	 * Childs nodes
	 * 
	 * (default value: [])
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $children = [];
	
	/**
	 * Create a new Colllection for childs
	 * 
	 * @access public
	 * @return void
	 */
	public function init()
	{
		$this->children = new Collection\Collection;
		$this->parent		= null;
	}
		
	/**
	 * Wrap a node, a tag str or throught a callback.
	 * 
	 * @access public
	 * @param string $tag (default: "")
	 * @return void
	 */
	public function wrap($input = null, $attrs = [], $text = null)
	{
		$node = $this->createNodeWith($input, $attrs, $text);
		
		// register current node node to the parent childs
		$node->children()->append($this);
		
		// setup the current parent
		$this->parent = $node;
		
		return $this;
	}
	
	/**
	 * Remove the current element from the node target.
	 * 
	 * @access public
	 * @return void
	 */
	public function unwrap()
	{
		if ($this->parent)
		{
			$this->parent->children()->remove($this);
			$this->parent = null;
		}
		return $this;
	}
	
	/**
	 * Append a new element
	 * 
	 * @access public
	 * @param Node $node
	 * @return void
	 */
	public function append($input = null, $attrs = [], $text = null)
	{
		$node	= $this->createNodeWith($input, $attrs, $text);
		$this->children->append($node);
		$node->parent = $this;
				
		return $this;
	}
	
	/**
	 * Append to target $node
	 * 
	 * @access public
	 * @param Node $node
	 * @return void
	 */
	public function appendTo(Node $node)
	{
		$clone = clone $this;
		
		if ($node->children()->append($clone))
		{
			$clone->parent = $node;
		}
		return $clone;
	}
	
	/**
	 * Prepend a child.
	 * 
	 * @access public
	 * @param Node $node
	 * @return void
	 */
	public function prepend($input = null, $attrs = [], $text = null)
	{
		$node = $this->createNodeWith($input, $attrs, $text);
		$this->children->prepend($node);
		
		// set the new text position
		if ($this->text->get())
		{
			$this->text->position($this->text->position() + 1);
		}
		
		return $this;
	}

	/**
	 * @access public
	 * @param Node $node
	 * @return void
	 */
	public function prependTo(Node $node)
	{
		$clone = clone $this;
		
		if ($node->children()->prepend($clone))
		{
			$clone->parent = $node;
		}
		
		// set the new text position
		if ($node->text->get())
		{
			$node->text->position($node->text->position() + 1);
		}
		
		return $clone;
	}
	
	/**
	 * Insert element before $node in collection
	 * 
	 * @access public
	 * @param mixed $node
	 * @return $this on success, false otherwise
	 */
	public function insertBefore(Node $node)
	{
		if (! $parent = $node->parent()) return false;
		
		$clone = clone $this;
		
		$parent->children()->insertBefore($node, $clone);
		$clone->parent = $node->parent;
				
		if ($node->index() >= ($position = $parent->text()->position()))
		{
			$parent->text()->position(++$position);
		}
		
		return $clone;
	}

	/**
	 * Insert $this before $node in collection.
	 * $node should be a part of collection
	 * 
	 * @access public
	 * @param mixed $node
	 * @return $this on success, false otherwise
	 */
	public function insertAfter(Node $node)
	{
		if (! $parent = $node->parent()) return false;

		$clone = clone $this;
		\Debug::dump($this);
		
		$parent->children()->insertAfter($node, $clone);
		$clone->parent = $node->parent;
		
		return $clone;
	}
	
	/**
	 * Replace the current node with $node in the collection.
	 * 
	 * @access public
	 * @return void
	 */
	public function replaceWith(Node $node)
	{
		if ($parent = $this->parent and $node !== $this)
		{
			$parent->children()->set($this->index(), $node);
		}
		
		return $this;
	}
			
	/**
	 * Delete a node from it parents.
	 * 
	 * @access public
	 * @return $this
	 */
	public function detach()
	{
		return $this->unwrap();
	}
		
	/**
	 * Reset the current instance after cloning.
	 * We need to clone dependencies in order
	 * to modify them later without propaging further modifications.
	 * 
	 * @access public
	 * @return void
	 */
	public function __clone()
	{
		// Deep cloning of childrens
		$this->children 	= unserialize(serialize($this->children));
		$this->parent			= null;
		$this->text				= clone $this->text;
		$this->attributes	= clone $this->attributes;
	}
	
	/**
	 * Try to create a new node.
	 * The method accepts:
	 * - string: the tag name
	 * - closure: return a string or a Node object
	 * - Node: instance of Node 
	 * 
	 * @access protected
	 * @param mixed $input
	 * @return Node or thrown InvalidArgumentException
	 */
	protected function createNodeWith($input, $attrs, $text)
	{
		// Get the closure results
		if ($input instanceof Closure)
		{
			$input = clone $input($this);
		}
		
		// The node is a  string ? instaciate it
		if (is_string($input) and $tag = trim($input))
		{
			$input = new Node($tag, $attrs, $text);
		}
		
		// Nothing valid at this point
		if (! $input instanceof Node)
		{
			throw new InvalidArgumentException("You Can wrap: a string, a node, or a compativle callback results");
		}
		
		return $input;
	}
}