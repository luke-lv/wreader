<?php
/**
 * 
 */
class ml_model_wruUserJob extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 0;
    const STATUS_DEL = 2;

    private $dataDefine;
    function __construct($dataDefine = '')
    {
        $this->dataDefine = $dataDefine;
        $db_config = ml_factory::load_standard_conf('dbUser');        //目前只有一个配置文件，所以

        parent::__construct('wru_userjob' , $db_config['wru_userjob']);
    }
    
    protected function hook_after_fetch()
    {
        
        if(isset($this->_data[0]['attend_tag']))
        {
            foreach ($this->_data as &$row) {
                $row['attend_tag'] = explode(',', $row['attend_tag']);
            }
        }
        else if(isset($this->_data['attend_tag']))
        {
            $this->_data['attend_tag'] = explode(',', $this->_data['attend_tag']);
        }

        if(isset($this->_data[0]['readmore_tag']))
        {
            foreach ($this->_data as &$row) {
                $row['readmore_tag'] = explode(',', $row['readmore_tag']);
            }
        }
        else if(isset($this->_data['attend_tag']))
        {
            $this->_data['readmore_tag'] = explode(',', $this->_data['readmore_tag']);
        }
    }
    
    protected function hook_before_write($array)
    {
        if(isset($array['attend_tag']))
            $array['attend_tag'] = is_array($array['attend_tag']) ? implode(',', $array['attend_tag']) : '';
        if(isset($array['readmore_tag']))
            $array['readmore_tag'] = is_array($array['readmore_tag']) ? implode(',', $array['readmore_tag']) : '';
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
    function std_updateRow($uid , $data = array())
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;
        
        $where = 'uid='.$uid;

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
}
?>