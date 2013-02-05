<?php

namespace HtmlNode;

use
	HtmlNode\Collection,
	HtmlNode\Collection\Attribute
;

class Compiler {
	
	/**
	 * @var mixed
	 * @access protected
	 */
	protected
		$node,
		$html,
		$attrs,
		$children,
		$autoclose,
		$text,
		$tag;
		
	/**
	 * @access public
	 * @param Node $node
	 * @return void
	 */
	public function __construct(NodeInterface $node)
	{
		$this->node				= $node;
		$this->children		= $node->children();
		$this->autoclose 	= $node->autoclose();
		$this->text				= $node->text();
		$this->tag				= $node->tag();
		$this->attrs			= '';
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
		$this->children->length() ? $this->children((bool)$text) : $this->html .= $text;
		
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
		$pos = $this->text->position();
		
		foreach ($this->children as $key => $child)
		{
			// If the node contains text, and we accept text rendering,
			// we insert the text at the current index.
			if ($text === true and $pos === $key)
			{
				$this->text();
			}
			
			$this->html .= $child;
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
		foreach ($this->node->attr() as $key => $data)
		{
			switch ($key)
			{
				case Attribute::KEY_CLASS:
				$this->classes($key, $data);
				break;
				case Attribute::KEY_STYLE:
				$this->styles($key, $data);
				break;
				case Attribute::KEY_DATA:
				case Attribute::KEY_ARIA:
				$this->data($key, $data);
				break;
				default:
				$this->attrToString($key, $data);
				break;
			}
		}
		$this->html .= '<'.$this->tag;
		$this->html .= $this->attrs ? ' '.trim($this->attrs) : '';
		$this->html .= $this->autoclose ? ' />' : '>';
		
		return $this->html;
	}
	
	/**
	 * Convert key / value pair to str attribute.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $val
	 * @return void
	 */
	public function attrToString($key, $val)
	{
		if (is_array($key)) $key = reset($key);
		
		$this->attrs .= $key.'="'.$val.'" ';
	}
	
	/**
	 * @access public
	 * @return $this
	 */
	public function close()
	{
		$this->html .= $this->autoclose ? '' : '</'.$this->tag.'>';
		
		return $this;
	}
	
	/**
	 * Get the compiled html.
	 * 
	 * @access public
	 * @return String
	 */
	public function html()
	{
		return $this->html;
	}
	
	/**
	 * Get the current node text.
	 * 
	 * @access public
	 * @return $this
	 */
	public function text()
	{
		$this->html .= $this->text->get();
		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function classes($key)
	{
		if ($classes = $this->node->attr($key))
		{
			 $this->attrToString($key, implode(" ", $classes));
		}
	}
	
	/**
	 * Flat multidimentionnnal $data arry to
	 * namespaced $bkey separated by $glue.
	 * 
	 * @access public
	 * @param string $key (default: "data")
	 * @return Array
	 */
	public function data($bkey = Attribute::KEY_DATA, $data)
	{
		static $ckey = [];
		
		foreach ($data as $key => $val)
		{
			$ckey[] = $key;
			
			if (is_array($val) and array_values($val) !== $val)
			{
				$this->data($bkey, $val);
			}
			else
			{
				$this->attrToString($bkey."-".implode("-", $ckey), $val);
			}
			array_pop($ckey);
		}
	}
	
	/**
	 * Parse inline styles
	 * 
	 * @access public
	 * @return void
	 */
	public function styles($key, $data)
	{
		$out = "";
		
		foreach ($data as $key => $val)
		{
			$out .= $key.':'.$val.';';
		}
		
		$out and $this->attrToString($key, $out);
	}
	
}