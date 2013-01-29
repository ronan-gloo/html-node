<?php

namespace HtmlNode;

use HtmlNode\Collection;

class Compiler {
	
	/**
	 * @var mixed
	 * @access protected
	 */
	protected $node;
	
	/**
	 * (default value: "")
	 * 
	 * @var string
	 * @access protected
	 */
	protected $hml;
	
	/**
	 * @access public
	 * @param Node $node
	 * @return void
	 */
	public function __construct(Node $node)
	{
		$this->node				= $node;
		$this->children		= $node->children();
		$this->autoclose 	= $node->autoclose();
		$this->text				= $node->text();
		$this->tag				= $node->tag();
	}

	/**
	 * Build the node.
	 * 
	 * @access public
	 * @return void
	 */
	public function node()
	{
		// Open the tag
		$this->open();
		$this->contents();
		$this->close();
		
		return $this->html;
	}
	
	/**
	 * @access public
	 * @return String
	 */
	public function contents()
	{
		$text = $this->text->get();
		
		// Get chiildren contents
		if ($this->children->length())
		{
			$this->children(!! $text);
		}
		else
		{
			$this->html .= $text;
		}
		return $this->html;
	}
	
	/**
	 * Add node children and optionnaly text to html
	 * 
	 * @access public
	 * @return String
	*/
	public function children($text = false)
	{
		$pos	= $this->text->position();
		
		foreach ($this->children as $key => $child)
		{
			$this->html .= $child instanceof Node ? $child->render() : $child;
			
			// If the node contains text, and we accept text rendering,
			// we insert the text at the current index.
			if ($text === true and $pos == $key)
			{
				$this->html .= $this->text->get();
			}
		}
		return $this->html;
	}
	
	/**
	 * Build the left part of the element.
	 * 
	 * @access public
	 * @return String
	 */
	public function open()
	{		
		$this->attrs = [];

		foreach ($this->node->attr() as $key => $data)
		{
			switch ($key)
			{
				case "class":
				$this->classes($data);
				break;
				case "style":
				$this->styles($data);
				break;
				case "data":
				case "aria":
				$this->data($data, $key);
				break;
				default:
				$this->attributes($data, $key);
				break;
			}
		}
		
		$attrs = "";

		foreach ($this->attrs as $property => $value)
		{
			$attrs .= $property.'="'.$value.'" ';
		}

		$this->html .= '<'.$this->tag;
		$this->html .= $attrs ? ' '.trim($attrs) : '';
		$this->html .= $this->autoclose ? ' />' : '>';
		
		return $this->html;
	}
	
	/**
	 * @access public
	 * @return String
	 */
	public function close()
	{
		$this->html .= $this->autoclose ? '' : '</'.$this->tag.'>';
		
		return $this->html;
	}
	
	/**
	 * Default behavior: set the key / val pair.
	 * @access protected
	 * @static
	 * @param mixed $node
	 * @return Array
	*/
	public function attributes($data, $key)
	{
		is_array($data) and $data = key($data);

		$this->attrs[$key] = $data;

		return $this->attrs;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function classes()
	{
		if ($classes = $this->node->attr("class"))
		{
			$this->attrs["class"] = implode(" ", $classes);
		}
		return $this->attrs;
	}
	
	/**
	 * Flat multidimentionnnal $data arry to
	 * namespaced $bkey separated by $glue.
	 * 
	 * @access public
	 * @param string $key (default: "data")
	 * @return Array
	 */
	public function data($data, $bkey = "data")
	{
		static $ckey = [];
		
		foreach ($data as $key => $val)
		{
			$ckey[] = $key;
			
			if (is_array($val) and array_values($val) !== $val)
			{
				$this->data($val, $bkey, "-");
			}
			else
			{
				$this->attrs[$bkey."-".implode("-", $ckey)] = $val;
			}
			array_pop($ckey);
		}
		$this->attrs;
	}
	
	/**
	 * Parse inline styles
	 * 
	 * @access public
	 * @return void
	 */
	public function styles($data)
	{
		$out = "";
		
		foreach ($data as $key => $val)
		{
			$out .= $key.':'.$val.';';
		}
		
		$out and $this->attrs["style"] = $out;
		
		return $this->attrs;
	}
	
}