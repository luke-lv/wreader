<?php
/**
 * 
 */
class ml_model_wrcJob2jobContent extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 0;
    const STATUS_DEL = 2;

    private $dataDefine;
    function __construct($dataDefine = '')
    {
        $this->dataDefine = $dataDefine;
        $db_config = ml_factory::load_standard_conf('dbContentbase');        //目前只有一个配置文件，所以

        parent::__construct('wrc_job2jobContent' , $db_config['wrc_job2jobContent']);
    }
    
    protected function hook_after_fetch()
    {
        if(isset($this->_data[0]['jobContentIds']))
        {
            foreach ($this->_data as &$row) {
                $row['jobContentIds'] = json_decode($row['jobContentIds'] , 1);
            }
        }
        else if(isset($this->_data['jobContentIds']))
        {
            $this->_data['jobContentIds'] = json_decode($this->_data['jobContentIds'] , 1);
        }

        if(isset($this->_data[0]['category']))
        {
            foreach ($this->_data as &$row) {
                $row['category'] = explode(',', $row['category']);
            }
        }
        else if(isset($this->_data['category']))
        {
            $this->_data['category'] = explode(',', $this->_data['category']);
        }
    }
    protected function hook_before_write($array)
    {
        if($array['category'])
            $array['category'] = is_array($array['category']) ? implode(',', $array['category']) : '';
        if($array['jobContentIds'])
            $array['jobContentIds'] = is_array($array['jobContentIds']) ? json_encode($array['jobContentIds']) : '';
        return $array;
    }
    function std_listByPage($page = 1 , $pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $page = $page <1 ? 1 : $page;
        $start = ($page-1)*$pagesize;
        $sql = 'select * from '.$this->table.' where status='.self::STATUS_NORMAL.' order by id desc limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }

    function std_getCount()
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $where = 'status = '.self::STATUS_NORMAL;
        return $this->fetch_count($where);
    }

    function std_getRowById($id)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where id='.$id;
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
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;

        $where = '`id` = '.$id;
        $data['status'] = self::STATUS_DEL;

        return $this->update($data , $where);

    }
    function get_by_jobid($job_id)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where job_id='.$job_id;
        return $this->fetch_row($sql);
    }
}
?>