<?php

namespace HtmlNode\Component;

trait Config {
	
	/**
	 * config
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $config = [
		"text" => true, // Accepts text
		//"register" 	=> true, // Register the object
	];
	
	/**
	 * Get / Set a config item.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $val (default: null)
	 * @return void
	 */
	public function config($key, $val = null)
	{
		if (isset($this->config[$key]))
		{
			if (func_num_args() === 1) return $this->config[$key];
			
			return $this->config[$key] = $val;
		}
	}
	
}