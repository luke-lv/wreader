<?php
/**
 * 
 */
class ml_model_wrcArticle extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 0;
    const STATUS_DEL = 2;

    private $dataDefine;
    function __construct()
    {
        $this->dataDefine = $dataDefine;
        $db_config = ml_factory::load_standard_conf('dbContentbase');        //目前只有一个配置文件，所以

        parent::__construct('wrc_article' , $db_config['wrc_article']);
    }
    
    function std_listBySrcIdByPage($srcId , $page = 1 , $pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $page = $page <1 ? 1 : $page;
        $start = ($page-1)*$pagesize;
        $sql = 'select * from '.$this->table.' where source_id = '.$srcId.' and status='.self::STATUS_NORMAL.' order by id desc limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }

    function std_getCountBySrcId($srcId)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $where = 'source_id = '.$srcId.' and status = '.self::STATUS_NORMAL;
        return $this->fetch_count($where);
    }

    function std_getRowById($id)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where id='.$id;
        return $this->fetch_row($sql);
    }

    function std_addRow($srcId , $data = array())
    {
        if(!$this->init_db($srcId , self::DB_MASTER))
            return false;
        
        $data['source_id'] = $srcId;
        $data['title_hash'] = $this->_title_hash($data['title']);


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


    function countByLink($srcId , $link)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $where = 'source_id = '.$srcId.' and link = "'.$link.'"';
        return $this->fetch_count($where);
    }
    function countByTitle($srcId , $title)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $where = 'source_id = '.$srcId.' and title_hash = "'.$this->_title_hash($title).'"';
        return $this->fetch_count($where);   
    }





    private function _title_hash($title)
    {
        return ml_tool_resid::str_hash($title);
    }
}
?>