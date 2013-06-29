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
        $array['tags'] = is_array($array['tags']) ? implode(',', $array['tags']) : '';
        return $array;
    }

    

    protected function hash_table($Ym)
    {
        return '_'.$Ym;
    }


    function std_listBySrcIdByPage($srcId  , $Ym =0, $page = 1 , $pagesize = 10)
    {
        $Ym = $Ym>0 ? $Ym : date('Ym');
        if(!$this->init_db($Ym , self::DB_SLAVE))
            return false;



        $page = $page <1 ? 1 : $page;
        $start = ($page-1)*$pagesize;
        $sql = 'select * from '.$this->table.' where source_id = '.$srcId.' and status='.self::STATUS_NORMAL.' order by id desc limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }

    function std_getCountBySrcId($srcId , $Ym = 0)
    {
        $Ym = $Ym>0 ? $Ym : date('Ym');
        if(!$this->init_db($Ym , self::DB_SLAVE))
            return false;
        
        $where = 'source_id = '.$srcId.' and status = '.self::STATUS_NORMAL;

        return $this->fetch_count($where);
    }

    function std_getRowById($id)
    {
        $Ym = $this->_calc_date_by_articleId($id);
        if(!$this->init_db($Ym , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where id="'.$id.'"';
        return $this->fetch_row($sql);
    }

    function std_addRow($article_id , $srcId , $data = array())
    {
        $Ym = $this->_calc_date_by_articleId($article_id);
        if(!$this->init_db($Ym , self::DB_MASTER))
            return false;
        
        $data['id'] = $article_id;
        $data['source_id'] = $srcId;
        $data['title_hash'] = $this->_title_hash($data['title']);


        return $this->insert($data);
    }
    function std_updateRow($id , $data = array())
    {
        $Ym = $this->_calc_date_by_articleId($id);
        if(!$this->init_db($Ym , self::DB_MASTER))
            return false;
        $dataDefine = ml_factory::load_dataDefine($this->dataDefine);

        $where = 'id='.$id;

        return $this->update($data , $where);
    }
    function std_delById($id)
    {
        $Ym = $this->_calc_date_by_articleId($id);
        if(!$this->init_db($Ym , self::DB_MASTER))
            return false;

        $where = '`id` = '.$id;
        $data['status'] = self::STATUS_DEL;

        return $this->update($data , $where);

    }


    function countByLink($srcId , $link)
    {
        $Ym = date('Ym');
        if(!$this->init_db($Ym , self::DB_SLAVE))
            return false;
        $where = 'source_id = '.$srcId.' and link = "'.$link.'"';
        return $this->fetch_count($where);
    }
    function countByTitle($srcId , $title)
    {
        $Ym = date('Ym');
        if(!$this->init_db($Ym , self::DB_SLAVE))
            return false;
        $where = 'source_id = '.$srcId.' and title_hash = "'.$this->_title_hash($title).'"';
        return $this->fetch_count($where);   
    }

    function getLatestArticle($Ym = 0 , $page = 1 , $pagesize=100)
    {
        $Ym = $Ym>0 ? $Ym : date('Ym');
        if(!$this->init_db($Ym , self::DB_SLAVE))
            return false;

        $sql = 'select * from '.$this->table.' order by _ctime desc';
        return $this->fetch($sql);
    }


    private function _calc_date_by_articleId($article_id)
    {
        $date = strtotime(base_convert(substr($article_id , 0 , 5),36,10));
        return date('Ym' , $date);
    }

    private function _title_hash($title)
    {
        return ml_tool_resid::str_hash($title);
    }
    public function hash_article_id($date , $srcId , $title)
    {
        return base_convert($date, 10, 36)
        .str_pad(bin2hex($srcId) , 5,0, STR_PAD_LEFT)
        .$this->_title_hash($title);
    }
}
?>