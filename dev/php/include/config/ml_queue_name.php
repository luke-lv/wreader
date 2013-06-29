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

define('ML_QUEUENAME_CONTENT2REDIS' , 'mlq_content2redis');


/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * 
 * 定义队列过程
 * 1，定义队列名称常量
 * 2，将常量加入$mq_key_config数组
 * 3，COPY ml_mq_dev_______template.php ,并修改相应信息，加入自己的逻辑
 * 16 17 41行，共有4处修改
 * 增加自己的注释和程序
 * 完成
 * 
 * 
 * 
 */
$mq_key_config = array(
    ML_QUEUENAME_CONTENT2REDIS => 1,

);




function ml_run_queue_check($queuename)
{
    return true;
    if(SYSDEF_SERVER_TYPE == 'dev')
        return true;
    global $mq_cron_run_ip;
    $local_ip = Tool_ip::getLocalLastIp(true , false);
    if($mq_cron_run_ip[$queuename] != $local_ip)
        die('no run here!');
}