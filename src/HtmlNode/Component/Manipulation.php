<?php

namespace HtmlNode\Component;

use
	Closure,
	InvalidArgumentException,
	HtmlNode\Node,
	HtmlNode\NodeInterface,
	LogicException
;

/**
 * Class Manipulation
 * @package HtmlNode\Component
 */
trait Manipulation {
	
	/**
	 * Parent Node
	 * 
	 * @var mixed
	 * @access public
	 */
	public $parent = null;
	
	/**
	 * Childs nodes
	 * 
	 * (default value: [])
	 * 
	 * @var mixed
	 * @access protected
	 */
	public $children = [];


    /**
     * Wrap a node, a tag str or with a callback
     * @param null $input
     * @param null $text
     * @param array $attrs
     * @return $this
     */
    public function wrap($input = null, $text = null, $attrs = [])
	{
		$node = $this->createNodeWith($input, $text, $attrs, false);
		
		// register current node node to the parent childs
		$node->children->append($this);
		
		// setup the current parent
		$this->parent = $node;
		
		return $this;
	}
	
	/**
	 * Remove the current element from the node target.
	 * @return $this
	 */
	public function unwrap()
	{
		if ($this->parent)
		{
			$this->parent->children->remove($this);
			$this->parent = null;
		}
		return $this;
	}

    /**
     * @param null $input
     * @param null $text
     * @param array $attrs
     * @return $this
     */
    public function append($input = null, $text = null, $attrs = [])
	{
		$node	= $this->createNodeWith($input, $text, $attrs);
		$this->children->append($node);
		$node->parent = $this;
				
		return $this;
	}

    /**
     * @param NodeInterface $node
     * @return Manipulation
     */
    public function appendTo(NodeInterface $node)
	{
		$clone = clone $this;
		
		if ($node->children->append($clone))
		{
			$clone->parent = $node;
		}
		return $clone;
	}


    /**
     * @param null $input
     * @param null $text
     * @param array $attrs
     * @return $this
     */
    public function prepend($input = null, $text = null, $attrs = [])
	{
		$node = $this->createNodeWith($input, $text, $attrs);
		$this->children->prepend($node);
		
		// set the new text position
		if ($this->text->get())
		{
			$this->text->position($this->text->position() + 1);
		}
		
		return $this;
	}

    /**
     * @param NodeInterface $node
     * @return Manipulation
     */
    public function prependTo(NodeInterface $node)
	{
		$clone = clone $this;
		
		if ($node->children->prepend($clone))
		{
			$clone->parent = $node;
		}
		// set the new text position
		if ($node->text->get())
		{
			$node->text->position($clone->index());
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
	public function insertBefore(NodeInterface $node)
	{
		if (! $parent = $node->parent()) return false;
		
		$clone = clone $this;
		
		$parent->children->insertBefore($node, $clone);
		
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
	public function insertAfter(NodeInterface $node)
	{
		if (! $parent = $node->parent()) return false;

		$clone = clone $this;
		
		$parent->children->insertAfter($node, $clone);
		$clone->parent = $node->parent;
		
		return $clone;
	}

    /**
     * @param NodeInterface $node
     * @return $this
     */
    public function replaceWith(NodeInterface $node)
	{
		if ($parent = $this->parent and $node !== $this)
		{
			$parent->children[$this->index()] = $node;
			$node->parent = $parent;
			$this->parent = null;
		}
		
		return $this;
	}

    /**
     *
     */
    public function detach()
	{
		//Util\Master::delete($this);
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
		// Deep cloning children
		$this->children = $this->children->copy();
		
		// reset the current parent
		$this->parent = null;
		
		// and clone dependecies
		$this->text = clone $this->text;
		$this->attributes = clone $this->attributes;
	}

    /**
     * Try to create a new node. The method accepts:
     * - string: the tag name
     * - closure: return a string or a Node object
     * - Node: instance of Node
     * @param $input
     * @param $text
     * @param $attrs
     * @param bool $checkClose
     * @return NodeInterface
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function createNodeWith($input, $text, $attrs, $checkClose = true)
	{
		// Do not append / prepend autoclosed elements
		if ($checkClose === true and in_array($this->tagname, static::$autoclosed))
		{
			throw new LogicException("You cannot add Node on self closed element");
		}
		
		// Get the closure results
		if ($input instanceof Closure)
		{
			$input = $input($this);
		}
		
		// Get the closure results
	    if ($input instanceof NodeInterface)
		{
			$input = clone $input;
		}

		// The node is a  string ? instaciate it
		elseif (is_string($input) and $tag = trim($input))
		{
			$input = Node::make($tag, $text, $attrs);
		}
		
		// Nothing valid at this point
		if (! $input instanceof NodeInterface)
		{
			throw new InvalidArgumentException("You Can wrap: a string, a node, or a compatible callback result");
		}
		
		return $input;
	}
}