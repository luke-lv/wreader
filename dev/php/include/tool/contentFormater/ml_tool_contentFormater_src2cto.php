<?php
//参见ml_tool_contentFormater_baseBySrc
//
class ml_tool_contentFormater_src2cto
{
	static function getContentByPage($html)
	{
		$tmp = substr($html, strpos($html, '<dd id="Article">')+17);
		$tmp = substr($tmp, 0 , strpos($tmp, '</dd>'));
		return $tmp;
	}

	public function outputFormatContent($content)
	{

		$content = str_replace('</div>', '<br/>', $content);
		return $content;
	}
}


