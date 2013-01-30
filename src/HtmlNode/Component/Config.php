<?php

namespace HtmlNode\Component;

trait Config {
	
	/**
	 * config
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $config = ["text" => true];
	
	/**
	 * Get / Set a config item.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $val (default: null)
	 * @return void
	 */
	public function config($key = null, $val = null)
	{
		if (! $key) return $this->config;
		
		if (is_string($key) and func_num_args() == 1)
		{
			return isset($this->config[$key]) ? $this->config[$key] : null;				
		}
		
		$this->config = (is_string($key) ? [$key => $val] : $key) + $this->config;

		return $this;
	}
	
}