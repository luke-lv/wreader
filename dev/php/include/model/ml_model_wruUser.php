<?php
/**
 * 
 */
class ml_model_wruUser extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 0;
    const STATUS_DEL = 2;

    private $dataDefine;
    function __construct()
    {
        $this->dataDefine = 'wruUser';
        $db_config = ml_factory::load_standard_conf('dbUser');        //目前只有一个配置文件，所以

        parent::__construct('wru_user' , $db_config['wru_user']);
    }
    
    protected function hook_after_fetch(){
        if(isset($this->_data[0]['id']))
        {
            foreach ($this->_data as &$row) {
                $row['uid'] = $row['id'];
            }
        }
        else if(isset($this->_data['id']))
        {
            $this->_data['uid'] = $this->_data['id'];
        }

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
}
?>