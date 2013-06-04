<?php
/**
 * 
 */
class ml_model_dbSource extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 1;
    const STATUS_DEL = 9;
/**
 * 创建构造函数
 *
 */
    function __construct()
    {
        $db_config = ml_factory::load_standard_conf('dbContentbase');        //目前只有一个配置文件，所以

        parent::__construct('wr_contentbase' , $db_config['wr_contentbase']);
        $this->table = 'wrc_source';
    }
    
    function listSourceByPage($page = 1 , $pagesize = 10)
    {
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

    function addSource($title , $rss , $domain , $other = array())
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;

        $data = array(
                'title' => $title,
                'start_date' => $startdate,
                'days' => $days,
            );
        return $this->insert($data);
    }
}
?>