<?php
//具体方法在子类中覆盖
//
//

class ml_tool_contentFormater_baseBySrc
{
	private function run($codeSign , $function , $param)
	{
		$classname = self::calcFormaterClassName($codeSign);

		$function = substr($function, strpos($function, '::')+2);

		
		if(class_exists($classname) && method_exists($classname, $function))
		{

			return $classname::$function($param);
		}
		else
		{
			return $param;
		}
	}
	public function formatLink($codeSign , $link)
	{
		return self::run($codeSign , __METHOD__ , $link);
	}
	public function getContentByPage($codeSign , $html)
	{
		return self::run($codeSign , __METHOD__ , $link);
	}

	public function outputFormatContent($codeSign , $content)
	{
		return self::run($codeSign , __METHOD__ , $content);
	}

	public function calcFormaterClassName($codeSign)
	{
		$pos = strpos($codeSign, '_');
		$len = $pos > 0 ? $pos : strlen($codeSign);
		return 'ml_tool_contentFormater_src'.ucfirst(substr($codeSign, 0 , $len));
	}
}