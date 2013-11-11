<?php
/**
 * 
 */
class ml_model_wrcSource extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 0;
    const STATUS_DEL = 2;
    const STATUS_STOP = 3;

    private $dataDefine;
    function __construct()
    {
        $this->dataDefine = $dataDefine;
        $db_config = ml_factory::load_standard_conf('dbContentbase');        //目前只有一个配置文件，所以

        parent::__construct('wrc_source' , $db_config['wrc_source']);
    }
    protected function hook_after_fetch()
    {
        if(isset($this->_data[0]['tags']))
        {
            foreach ($this->_data as &$row) {
                $row['tags'] = explode(',', $row['tags']);
            }
        }
        else if(isset($this->_data['tags']))
        {
            $this->_data['tags'] = explode(',', $this->_data['tags']);
        }

    }
    
    protected function hook_before_write($array)
    {
        if($array['tags'])
            $array['tags'] = is_array($array['tags']) ? implode(',', $array['tags']) : '';
    
        return $array;
    }
    
    function std_listByPage($page = 1 , $pagesize = 10 , $admin = 0 , $category = 0, $orderbyLastSpider = true)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $page = $page <1 ? 1 : $page;
        $start = ($page-1)*$pagesize;


        $statusCondition = $admin == 1 ? ' status != '.self::STATUS_DEL : ' status = '.self::STATUS_NORMAL;
        $condition = $category > 0 ? ' AND category='.$category :'';
        if($orderbyLastSpider)
            $order = 'order by `lastUpdateTime` DESC';
        $sql = 'select * from '.$this->table.' where '.$statusCondition.$condition.' '.$order.' limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }

    function std_getCount($admin = 0 , $category = 0)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $statusCondition = $admin == 1 ? 'status != '.self::STATUS_DEL : 'status = '.self::STATUS_NORMAL;
        $condition = $category > 0 ? ' AND category='.$category :'';
        return $this->fetch_count($statusCondition.$condition);
    }

    function std_getRowById($id , $admin = 0)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $statusCondition = $admin == 1 ? ' and status != '.self::STATUS_DEL : ' and status = '.self::STATUS_NORMAL;
        $sql = 'select * from '.$this->table.' where id='.$id.$statusCondition;
        return $this->fetch_row($sql);
    }

    function std_addRow($data = array())
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;
        $dataDefine = ml_factory::load_dataDefine($this->dataDefine);

        return $this->insert($data);
    }
    function std_updateRow($id , $data = array())
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;
        $dataDefine = ml_factory::load_dataDefine($this->dataDefine);

        $where = 'id='.$id;

        return $this->update($data , $where);
    }
    function std_delById($id)
    {
        return $this->std_setStatusById($id , self::STATUS_DEL);

    }
    function std_setStatusById($id , $status)
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;

        $where = '`id` = '.$id;
        $data['status'] = $status;

        return $this->update($data , $where);

    }
    function getRowsByIds($aId , $admin = 0)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $statusCondition = $admin == 1 ? ' and status != '.self::STATUS_DEL : ' and status = '.self::STATUS_NORMAL;
        $sql = 'select * from '.$this->table.' where id in ('.implode(',', $aId).')'.$statusCondition;
        return $this->fetch($sql);
    }

    function listBySpidertime($spider_time , $page , $pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $page = $page <1 ? 1 : $page;
        $start = ($page-1)*$pagesize;


        $sql = 'select * from '.$this->table.' where spider_time='.$spider_time.' and status='.self::STATUS_NORMAL.' order by id desc limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }
    function updateLastSpiderTime($id)
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;

        $this->_is_utime = false;

        $where = '`id` = '.$id;
        $data['lastUpdateTime'] = date('Y-m-d H:i:s');

        return $this->update($data , $where);
    }
}
?>