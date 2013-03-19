<?php

namespace HtmlNode\Component;

use LogicException;

trait Text {
	
	/**
	 * The text dependencie
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $text;

	/**
	 * Set the node text.
	 * 
	 * @access public
	 * @param bool $text (default: false)
	 * @return void
	 */
	public function text($text = false)
	{
		if (func_num_args() === 0) return $this->text;
		
		if ($this->autoclose === true)
		{
			throw new LogicException("Cannot add text on ".$this->tagname." element");
		}
		$this->text->position($this->children()->length());
		$this->text->set($text);
	
		return $this;
	}
	
	/**
	 * An alias to Dependency\Text::contains().
	 */
	public function contains($str, $case = false, $strict = false)
	{
		return $this->text->contains($str, $case, $strict);
	}	

	
}