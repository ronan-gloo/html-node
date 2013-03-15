<?php

namespace HtmlNode;

interface CompilerInterface {
	
	/**
	 * Attach a node.
	 * 
	 * @access public
	 * @param NodeInterface $node
	 * @return void
	 */
	public function node(NodeInterface $node);
	
	/**
	 * Compiler the entire node.
	 * 
	 * @access public
	 * @return void
	 */
	public function compile();
	
	/**
	 * Get the openning tag.
	 * 
	 * @access public
	 * @return void
	 */
	public function open();
	
	/**
	 * Get the close tag.
	 * 
	 * @access public
	 * @return void
	 */
	public function close();
	
	/**
	 * Get the node text.
	 * 
	 * @access public
	 * @return void
	 */
	public function text();
	
	/**
	 * Parse node childs.
	 * 
	 * @access public
	 * @return void
	 */
	public function children();
	
	/**
	 * The node contents.
	 * 
	 * @access public
	 * @return void
	 */
	public function contents();

	/**
	 * The stored string of compiled node.
	 * 
	 * @access public
	 * @return void
	 */
	public function html();

}