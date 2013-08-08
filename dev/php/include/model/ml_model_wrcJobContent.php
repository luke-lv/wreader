<?php
/**
 * 
 */
class ml_model_wrcJobContent extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 0;
    const STATUS_DEL = 2;

    private $dataDefine;
    function __construct($dataDefine = '')
    {
        $this->dataDefine = $dataDefine;
        $db_config = ml_factory::load_standard_conf('dbContentbase');        //目前只有一个配置文件，所以

        parent::__construct('wrc_jobContent' , $db_config['wrc_jobContent']);
    }
    
    function std_listByPage($page = 1 , $pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $page = $page <1 ? 1 : $page;
        $start = ($page-1)*$pagesize;
        $limit = $pagesize > 0
            ? (' limit '.$start.','.$pagesize)
            : '';
        $sql = 'select * from '.$this->table.' where status='.self::STATUS_NORMAL.' order by id desc'.$limit;
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

    function get_by_name_type($contentName_tagid , $contentType_tagid)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where contentName_tagid='.$contentName_tagid.' and contentType_tagid='.$contentType_tagid.' and status = '.self::STATUS_NORMAL;
        return $this->fetch_row($sql);   
    }
    function get_by_ids($aId)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where id in ('.implode(',' , $aId).') and status = '.self::STATUS_NORMAL;

        return $this->fetch($sql);   
    }
    function get_by_category($category , $page=1   , $pagesize = 10 , $contentName_tagid = 0)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        if($pagesize > 0)
        {
            $start = ($page-1)*$pagesize;
            $limit = ' limit '.$start.' , '.$pagesize;
        }
        
        if($contentName_tagid)
            $cn_condition = ' and contentName_tagid = '.$contentName_tagid;
        $sql = 'select * from '.$this->table.' where category = '.$category.$cn_condition.' and status = '.self::STATUS_NORMAL.$limit;
        return $this->fetch($sql);   
    }

    function count_by_category($category , $contentName_tagid = 0)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        if($contentName_tagid)
            $cn_condition = ' and contentName_tagid = '.$contentName_tagid;
        $sql = 'category = '.$category.$cn_condition.' and status = '.self::STATUS_NORMAL;
        return $this->fetch_count($sql);   
    }
function get_contentName_by_category($category , $page , $pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        
        $sql = 'select distinct(contentName_tagid) from '.$this->table.' where category = '.$category.' and status = '.self::STATUS_NORMAL;
        return $this->fetch($sql);   
    }
    function get_by_categorys($aCategory)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where category in ('.implode(',', $aCategory).') and status = '.self::STATUS_NORMAL;
        return $this->fetch($sql);   
    }
}
?>