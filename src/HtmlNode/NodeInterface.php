<?php

namespace HtmlNode;

interface NodeInterface {
	
	public function tag($tag = "");
	
	public function text($text = false);
	
	public function attr($name = null, $val = null);
	
	public function css($key, $val = null);
	
	public function data($key = null, $val = null);
	
	public function aria($key = null, $val = null);
	
	public function children($child = null);
	
	public function is($input);
	
	public function autoclose();
	
	public function parent();
	
	public function render($childs = true);
	
	public function __toString();
	
}