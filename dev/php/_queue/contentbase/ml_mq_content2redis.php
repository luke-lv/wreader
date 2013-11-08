<?php
/**
 * @copyright meila.com
 * @author shaopu@
 * @name 
 * @param 
 *         $xxx = 作用
 * @static 
 *         XXX = 作用
 * 
 * 
 */
$dir = dirname(dirname(__FILE__));

include($dir.'/__queue_global.php');
include_once(SERVER_ROOT_PATH.'/include/config/ml_queue_name.php');

class ml_mq_content2redis extends MqClass{
    const QUEUE_NAME = ML_QUEUENAME_CONTENT2REDIS;

    private $oBizA2R;
    protected function _construct()
    {
        $this->oBizA2R = new ml_biz_articleid2redis();
    }
    public function run_job(){
        //接收的数据
        $arr = $this->src_data;
        
        $aTag = explode(' ', $arr['tags']);
        $this->oBizA2R->execute($arr['article_id'] , $aTag , $arr['jobContentId']);
        
        return true;
    }

}

$mq_conf = ml_factory::load_standard_conf('redis');

$xblog_obj = new ml_mq_content2redis(new RsQueue(ml_mq_content2redis::QUEUE_NAME , $mq_conf['meila_queue']));
$argv[1]   = __FILE__;
$xblog_obj->setArgv($argv[1]);
$xblog_obj->execute();
?>