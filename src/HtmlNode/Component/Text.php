<?php

namespace HtmlNode\Component;

use LogicException;

/**
 * Class Text
 * @package HtmlNode\Component
 */
trait Text {
	
	/**
	 * The text dependency
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $text;

    /**
     * @param bool $text
     * @return $this|mixed
     * @throws \LogicException
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
     * @param $str
     * @param bool $case
     * @param bool $strict
     * @return mixed
     */
    public function contains($str, $case = false, $strict = false)
	{
		return $this->text->contains($str, $case, $strict);
	}
	
}