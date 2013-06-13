<?php
class ml_lib_xmlparser
{
	private $parser;
	public function __construct()
	{
		$this->parser = xml_parser_create('utf-8');
	}
	public function start($str)
	{
		
		xml_parse($this->parser, $str);

	}
	private

}
function ml_lib_xmlparser_func_start(){}
function ml_lib_xmlparser_func_end(){}
