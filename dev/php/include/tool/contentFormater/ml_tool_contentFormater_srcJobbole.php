<?php
//参见ml_tool_contentFormater_baseBySrc
//
class ml_tool_contentFormater_srcJobbole
{
	static public function formatLink($link)
	{
		return substr($link, 0 , strpos($link, '?'));
	}
}

