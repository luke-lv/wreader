<?php
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

}