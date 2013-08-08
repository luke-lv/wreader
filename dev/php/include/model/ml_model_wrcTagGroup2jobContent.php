<?php
/**
 * 
 */
class ml_model_wrcTagGroup2jobContent extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 0;
    const STATUS_DEL = 2;

    private $dataDefine;
    function __construct($dataDefine = '')
    {
        $this->dataDefine = $dataDefine;
        $db_config = ml_factory::load_standard_conf('dbContentbase');        //目前只有一个配置文件，所以

        parent::__construct('wrc_tagGroup2jobContent' , $db_config['wrc_tagGroup2jobContent']);
    }
    
    protected function hook_after_fetch()
    {
        if(isset($this->_data[0]['tags']))
        {
            foreach ($this->_data as &$row) {
                $row['tags'] = explode(' ',$row['tags']);
            }
        }
        else if(isset($this->_data['tags']))
        {
            $this->_data['tags'] = explode(' ',$this->_data['tags']);
        }

    }
    protected function hook_before_write($array)
    {
        if($array['tags'])
            $array['tags'] = is_array($array['tags']) ? implode(' ', $array['tags']) : '';
        return $array;
    }

    function std_listByPage($page = 1 , $pagesize = 10 , $category = 0)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $page = $page <1 ? 1 : $page;
        $start = ($page-1)*$pagesize;
        if($category > 0)
            $ctg_condition = ' category ='.$category.' and ';
        $sql = 'select * from '.$this->table.' where '.$ctg_condition.' status='.self::STATUS_NORMAL.' order by id desc limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }

    function std_getCount($category = 0)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        if($category > 0)
            $ctg_condition = ' category ='.$category.' and ';
        $where = $ctg_condition.'status = '.self::STATUS_NORMAL;
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

    function get_by_contentName_tagid($tagid)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where contentName_tagid='.$tagid;
        return $this->fetch($sql);
    }
}
?>