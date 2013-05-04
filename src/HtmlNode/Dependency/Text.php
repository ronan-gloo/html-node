<?php

namespace HtmlNode\Dependency;

use InvalidArgumentException;

/**
 * Class Text
 * @package HtmlNode\Dependency
 */
class Text implements Node {
	
	/**
	 * @var mixed
	 * @access protected
	 */
	protected $text;
	
	/**
	 * The text position
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $position;

    /**
     * @param null $string
     */
    public function __construct($string = null)
	{
		$string and $this->set($string);
		$this->position = 0;
	}
	
	/**
	 * @access public
	 * @return String
	 */
	public function __toString()
	{
		return $this->get();
	}

    /**
     * The relative text position.
     * If will be used by the compiler, in order
     * to set the text position relative to the node childs
     * @param int $int
     * @return $this|int|mixed
     */
    public function position($int = 0)
	{
		if (func_num_args() === 0)
		{
			return $this->position;
		}
	
		$this->position = (int)$int;
		
		return $this;
	}

    /**
     * @param $string
     * @return string
     * @throws \InvalidArgumentException
     */
    public function set($string)
	{
		if (! $this->isValidText($string))
		{
			throw new InvalidArgumentException('You cannot provide '.gettype($string).'s as text.');
		}
		return $this->text = strip_tags($string);
	}
	
	/**
	 * @access public
	 * @return String
	 */
	public function get()
	{
		return $this->text;
	}
	
	/**
	 * Returns the string legnth.
	 * 
	 * @access public
	 * @return int
	 */
	public function length()
	{
		return strlen($this->text);
	}

    /**
     * @param $str
     * @param bool $case
     * @param bool $strict
     * @return bool
     */
    public function contains($str, $case = false, $strict = false)
	{
		// make the search case insensitive ? 
		$func = $case === false ? "stripos" : "strpos";
		
		return $func($this->text, ($strict === false ? (string)$str : $str)) !== false;
	}

    /**
     * @param $tor
     * @param $re
     * @return $this
     */
    public function replace($tor, $re)
	{
		$this->text = str_replace($tor, $re, $this->text);
		return $this;
	}

    /**
     * @param $expr
     * @return int
     */
    public function match($expr)
	{
		return preg_match($expr, $this->text);
	}
	
	/**
	 * Check if php can force transtype of $string to string.
	 * 
	 * @access public
	 * @param mixed $string
	 * @return Bool
	 */
	protected function isValidText($string) {
		return !
			is_array($string)
			or (
				is_object($string) and ! method_exists($string, '__toString')
			);
	}
	
}