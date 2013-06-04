<?php
/**
 * 
 */
class ml_model_dbTrip extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 1;
    const STATUS_DEL = 9;
/**
 * 创建构造函数
 *
 */
    function __construct()
    {
        /**
         * 加载数据库配置
         */
        $db_config = ml_factory::load_standard_conf('dbContent');        //目前只有一个配置文件，所以
        /**
         * 构造函数
         * 参数：
         * 1，当前模型名称
         * 2，相关数据库配置
         */
        parent::__construct('trip' , $db_config['trip']);
    }
    
    function getTripListByUid($uid , $page = 1 , $pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $start = ($page-1)*$pagesize;
        $sql = 'select * from '.$this->table.' where uid = '.$uid.' and status = '.self::STATUS_NORMAL.' order by id desc limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }

    function getTripCountByUid($uid)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $where = 'uid = '.$uid.' and status = '.self::STATUS_NORMAL;
        return $this->fetch_count($where);
    }

    function addTripByUid($uid , $startdate , $days , $title)
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;

        $data = array(
                'uid' => $uid,
                'title' => $title,
                'start_date' => $startdate,
                'days' => $days,
            );
        return $this->insert($data);
    }
}
?>