<?php
include_once('../../__global.php');
include_once(SERVER_ROOT_PATH.'/3rd/readability.php');
class ml_tool_rssContent
{
	static public function rss2article($string)
	{
		$string = htmlspecialchars_decode($string);
		if(substr($string, 0 , 9) == '<![CDATA[')
		{
			$string = substr($string, 9 , strlen($string)-9-3);
		}
		return $string;
	}
	static public function parseHtml2Content($str , $charset='utf-8')
	{
		$str = substr($str, 0 , strpos($str, '</html>')+7);
		$str = substr($str, strpos($str, '<html')+7);


		$o = new Readability($str , $charset);
		$rs = $o->getContent();

		return $rs['content'];
	}
}

/*
//$a = file_get_contents('http://www.huxiu.com/article/15395/1.html');
$a = file_get_contents('http://www.huxiu.com/article/15395/1.html');

//var_dump($a);
// echo "\n\n\n\n\n\n";
var_dump(ml_tool_rssContent::parseHtml2Content($a));
*/