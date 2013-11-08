<?php
class ml_tool_chineseSegment
{
	const IGNORE_SYMBOL=true;
	public static function segment2word($str)
    {
        $scws = self::_init_scws();
        $scws->send_text($str);

        
        while ($result = $scws->get_result())
        {
        
            foreach ($result as $tmp){
                if(in_array($tmp['word'], $aBlack))
                    continue;

                $words[] = $tmp['word'];
            }
        }

        return $words;

    }
    public static function segmentWithAttr($str)
    {
    	$scws = self::_init_scws();
    	$scws->set_duality(true); 
        $scws->send_text($str);
        while ($result = $scws->get_result())
        {

            foreach ($result as $tmp){
                $rs[] = $tmp;
            }
        }
        return $rs;
    }
    private function _init_scws()
    {
    	$scws = scws_new();
        $scws->set_charset('utf8');
        $scws->set_dict(SERVER_ROOT_PATH.'/include/config/scws/dict.utf8.xdb');
        $scws->set_rule(SERVER_ROOT_PATH.'/include/config/scws/rules.utf8.ini');
        $scws->add_dict(SERVER_ROOT_PATH.'/include/config/scws/wreader.xdb');
        //$scws->add_dict(SERVER_ROOT_PATH.'/include/config/scws/dict_huxiu.txt', SCWS_XDICT_TXT);
        
        $scws->set_ignore(self::IGNORE_SYMBOL); 
        
        return $scws;
    }
}