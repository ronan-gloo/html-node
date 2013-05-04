<?php

namespace HtmlNode;

use
	HtmlNode\Collection,
	HtmlNode\Collection\Attribute
;

/**
 * Class Compiler
 * @package HtmlNode
 */
class Compiler implements CompilerInterface {
	
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
     * @param NodeInterface $node
     */
    public function __construct(NodeInterface $node = null)
	{
		$node and $this->node($node);
	}
	
	/**
	 * Attach a node to the current instance.
	 * 
	 * @access public
	 * @param NodeInterface $node
	 * @return $this
	 */
	public function node(NodeInterface $node)
	{
		$this->node		    = $node;
		$this->children     = $node->children();
		$this->autoclose    = $node->autoclose();
		$this->text		    = $node->text();
		$this->tag			= $node->tag();
		$this->attrs		= '';
		
		return $this;
	}

	/**
	 * Build the node.
	 * 
	 * @access public
	 * @return string
	 */
	public function compile()
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
		// Get children contents
		$this->children->length() ? $this->children() : $this->html .= $this->text->get();
		
		return $this;
	}

    /**
     * Add node children and optionnaly text to html
     * @param bool $withText
     * @return $this
     */
    public function children($withText = true)
	{
		$pos    = $this->text->position();
        $text   = $this->text->get();
		
		foreach ($this->children as $key => $child)
		{
			// If the node contains text, and we accept text rendering,
			// we insert the text at the current index.
			if ($withText === true and $pos === $key)
			{
                $this->html .= $text;
				$text  = false;
			}
            $this->html .= $child;
		}
		if ($withText === true and $text !== false)
		{
            $this->html .= $text;
		}
		return $this;
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
				default:
				$this->attrToString($key, $data);
				break;
				case Attribute::key_class:
				$this->classes($data);
				break;
				case Attribute::key_style:
				$this->styles($data);
				break;
				case Attribute::key_data:
				case Attribute::key_aria:
				$this->data($data);
				break;
			}
		}
		$this->html .= '<'.$this->tag;
		$this->html .= $this->attrs ? ' '.trim($this->attrs) : '';
		$this->html .= $this->autoclose ? ' />' : '>';
		
		return $this;
	}
	
	/**
	 * Convert key / value pair to str attribute.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $val
	 * @return $this
	 */
	public function attrToString($key, $val)
	{
		if (is_array($key)) $key = reset($key);
		
		$this->attrs .= $key.'="'.$val.'" ';

        return $this;
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
     * @return $this
     */
    public function classes()
	{
		if ($classes = $this->node->attr(Attribute::key_class))
		{
			 $this->attrToString(Attribute::key_class, implode(" ", $classes));
		}
        return $this;
	}


    /**
     * Flat multi-dimentionnnal $data array to
     * namespaced $bkey separated by $glue.
     * @param $data
     * @return $this
     */
    public function data($data)
	{
		static $cKey = [];
		
		foreach ($data as $key => $val)
		{
            $cKey[] = $key;
			
			if (is_array($val) and array_values($val) !== $val)
			{
				$this->data($val);
			}
			else
			{
				$this->attrToString(Attribute::key_data.'-'.implode('-', $cKey), $val);
			}
			array_pop($cKey);
		}
        return $this;
	}

    /**
     * @param $data
     * @return $this
     */
    public function styles($data)
	{
		$out = '';
		
		foreach ($data as $key => $val)
		{
			$out .= $key.':'.$val.';';
		}
		
		$out and $this->attrToString(Attribute::key_style, $out);

        return $this;
	}
	
}