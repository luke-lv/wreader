<?php
class ml_tool_chineseSegment
{
	const IGNORE_SYMBOL=true;

    static $_unusefulAttr = array('c' , 'uj' , 'r' , 'd' , 'm' , 'p' , 'f' , 'un' , 'q' , 'mt');
    static $_unuseChar = array('的' , '了' , '为' , '是' , '将' , '在' , '可以' , '也');
	public static function segment2word($str)
    {
        $scws = self::_init_scws();
        $scws->send_text($str);

        
        while ($result = $scws->get_result())
        {
        
            foreach ($result as $tmp){
                if(in_array($tmp['attr'], self::$_unusefulAttr))
                    continue;

                $words[] = $tmp['word'];
            }
        }

        return $words;

    }
    /**
     * 分词 并保存词的属性
     * @param  [type]  $str         [description]
     * @param  boolean $set_duality 二分
     * @return [type]               [description]
     */
    public static function segmentWithAttr($str , $set_duality = true , $with_symbol = false)
    {
    	$scws = self::_init_scws();
        if($with_symbol == false){
            $scws->set_ignore(self::IGNORE_SYMBOL); 
        }
    	$scws->set_duality($set_duality); 
        $scws->send_text($str);
        while ($result = $scws->get_result())
        {

            foreach ($result as $tmp){
                // if(in_array($tmp['attr'] , self::$_unusefulAttr)){
                //     continue;
                // }
                $rs[] = $tmp;
            }
        }
        return $rs;
    }
    private function _init_scws($with_symbol = false)
    {
    	$scws = scws_new();
        $scws->set_charset('utf8');
        $scws->set_dict(SERVER_ROOT_PATH.'/include/config/scws/dict.utf8.xdb');
        $scws->set_rule(SERVER_ROOT_PATH.'/include/config/scws/rules.utf8.ini');
        $scws->add_dict(SERVER_ROOT_PATH.'/include/config/scws/wreader.xdb');
        //$scws->add_dict(SERVER_ROOT_PATH.'/include/config/scws/dict_huxiu.txt', SCWS_XDICT_TXT);
        
        
        return $scws;
    }

    public static function filterUnavailableStr($content){
        $content = strip_tags($content);
        $content = str_replace('&nbsp;', ' ', $content);
        $content = Tool_string::delLongEnglish($content);
        $content = strtolower($content);
        return $content;
    }
}