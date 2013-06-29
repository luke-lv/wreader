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

    private $oTag;
    private $oRdsCB;
    protected function _construct()
    {
        $this->oTag = new ml_model_admin_dbTag();
        $this->oRdsCB = new ml_model_rdsContentBase();
    }
    public function run_job(){
        //接收的数据
        $arr = $this->src_data;
        
        $aTag = explode(' ', $arr['tags']);
        $aTag = array_filter($aTag);
        if(empty($aTag))
            return false;

        $this->oTag->core_tag_get_by_tags($aTag);
        $aCoreTag = $this->oTag->get_data();

        if(!empty($aCoreTag))
        {
            foreach ($aCoreTag as $key => $value) {
                $this->oRdsCB->addArticleToTag($value['tag_hash'] , $arr['article_id']);
            }
        }
        
        return true;
    }

}

$mq_conf = ml_factory::load_standard_conf('redis');

$xblog_obj = new ml_mq_content2redis(new RsQueue(ml_mq_content2redis::QUEUE_NAME , $mq_conf['meila_queue']));
$argv[1]   = __FILE__;
$xblog_obj->setArgv($argv[1]);
$xblog_obj->execute();
?>