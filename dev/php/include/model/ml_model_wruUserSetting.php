<?php
/**
 * 
 */
class ml_model_wruUserSetting extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 0;
    const STATUS_DEL = 2;

    private $dataDefine;
    function __construct($dataDefine = '')
    {
        $this->dataDefine = $dataDefine;
        $db_config = ml_factory::load_standard_conf('dbUser');        //目前只有一个配置文件，所以

        parent::__construct('wru_userSetting' , $db_config['wru_userSetting']);
    }

    protected function hook_after_fetch()
    {

        if(isset($this->_data[0]['data']))
        {
            foreach ($this->_data as &$row) {
                $row['data'] = json_decode($row['data'] , 1);
            }
        }
        else if(isset($this->_data['data']))
        {
            $this->_data['data'] = json_decode($this->_data['data'] , 1);
        }
    }
    
    protected function hook_before_write($array)
    {
        if($array['data'])
            $array['data'] = json_encode($array['data']);
        return $array;
    }
    
    function getByUidType($uid , $type)
    {

        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where uid='.$uid.' and type = '.$type;
        
        return $this->fetch_row($sql);
    }

    function setByUidType($uid , $type , $data = array())
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;
        
        $fields = array(
            'uid' => $uid,
            'type' => $type,
            'data' => $data,
        );
        return $this->replace($fields);
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