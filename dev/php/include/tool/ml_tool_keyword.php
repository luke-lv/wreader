<?php

class ml_tool_keyword
{
    static $sensitiveWord;
    static public function haveBasicKillWord($string)
    {
        $string = strtolower($string);
        $aKillword = ml_factory::load_standard_conf('basicKillWord');
        foreach ($aKillword as $key)
        {
            if(strpos($string , $key) !== false)
                return false;
        }
        return true;
    }
    static public function haveSensitiveWord($string){
        $string = strtolower($string);
        if(empty(self::$sensitiveWord))
            self::$sensitiveWord = ml_factory::load_standard_conf('sensitiveWord');
            
        foreach (self::$sensitiveWord as $key){
            if(strpos($string , $key) !== false)
                return true;
        }
        return false;
    }
    static public function filterSensitiveWord($string){
        $senWord = ml_factory::load_standard_conf('sensitiveWord');
        $replacements = array_pad(array(),count($senWord),"**");;
        return str_replace($senWord,$replacements,$string);
    }
}