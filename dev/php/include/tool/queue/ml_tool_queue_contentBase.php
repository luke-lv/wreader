<?php
include_once(SERVER_ROOT_PATH.'/include/config/ml_queue_name.php');

class ml_tool_queue_contentBase extends ml_tool_queue_base
{
    static public function add_content2redis($id , $aTags , $jobContentId){

        $key = ML_QUEUENAME_CONTENT2REDIS;
        $data['article_id'] = $id;
        $data['tags'] = implode(' ' , $aTags);
        $data['jobContentId'] = $jobContentId;
        return self::send_mq($key , $data);
    }
}