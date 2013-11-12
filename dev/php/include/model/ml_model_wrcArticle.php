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

        if(isset($this->_data[0]['jobContentId']))
        {
            foreach ($this->_data as &$row) {
                $row['jobContentId'] = explode(',', $row['jobContentId']);
            }
        }
        else if(isset($this->_data['jobContentId']))
        {
            $this->_data['jobContentId'] = explode(',', $this->_data['jobContentId']);
        }
    }
    
    protected function hook_before_write($array)
    {
        if($array['tags'])
            $array['tags'] = is_array($array['tags']) ? implode(',', $array['tags']) : '';
        if($array['jobContentId'])
            $array['jobContentId'] = is_array($array['jobContentId']) ? implode(',', $array['jobContentId']) : '';
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
        $limit = '';
        if($pagesize > 0)
            $limit = 'limit '.$start.','.$pagesize;
        $sql = 'select * from '.$this->table.' where source_id = '.$srcId.' and status='.self::STATUS_NORMAL.' order by id desc '.$limit;
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
        $Ym = $this->_calc_Ym_by_articleId($id);
        if(!$this->init_db($Ym , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where id="'.$id.'"';
        return $this->fetch_row($sql);
    }

    function std_addRow($article_id , $srcId , $data = array())
    {
        $Ym = $this->_calc_Ym_by_articleId($article_id);
        if(!$this->init_db($Ym , self::DB_MASTER))
            return false;
        
        $data['id'] = $article_id;
        $data['source_id'] = $srcId;
        $data['title_hash'] = $this->_title_hash($data['title']);


        return $this->insert($data);
    }
    function std_updateRow($id , $data = array())
    {
        $Ym = $this->_calc_Ym_by_articleId($id);
        if(!$this->init_db($Ym , self::DB_MASTER))
            return false;
        $dataDefine = ml_factory::load_dataDefine($this->dataDefine);

        $where = 'id="'.$id.'"';

        return $this->update($data , $where);
    }
    function std_delById($id)
    {
        $Ym = $this->_calc_Ym_by_articleId($id);
        if(!$this->init_db($Ym , self::DB_MASTER))
            return false;

        $where = '`id` = "'.$id.'"';
        $data['status'] = self::STATUS_DEL;

        return $this->update($data , $where);

    }
    function std_realDelById($id)
    {
        $Ym = $this->_calc_Ym_by_articleId($id);
        if(!$this->init_db($Ym , self::DB_MASTER))
            return false;

        $where = '`id` = "'.$id.'"';
        return $this->delete($where);
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
    public function getArticleByIds($aAid)
    {
        if(empty($aAid))
            return true;


        foreach ($aAid as $aid) {
            $Ym = $this->_calc_Ym_by_articleId($aid);
            $aYmAid[$Ym][] = $aid;
        }

        $aArticle = array();
        foreach ($aYmAid as $Ym => $aAidYm) {
            if(!$this->init_db($Ym , self::DB_SLAVE))
                return false;
            $sql = 'select * from '.$this->table.' where id in ('.$this->_build_in_sql($aAidYm).')';
            $rs = $this->fetch($sql);

            $aArticle = array_merge($aArticle , $this->get_data());
        }
        $aAid2Row = Tool_array::format_2d_array($aArticle , 'id' , Tool_array::FORMAT_FIELD2ROW);
        foreach ($aAid as $key => $value) {
            $aRs[] = $aAid2Row[$value];
        }
        
        $aRs = array_filter($aRs);

        $this->set_data($aRs);
        return true;
    }


    public function _calc_Ym_by_articleId($article_id)
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
    private function _build_in_sql($aAid)
    {
        return '"'.implode('","', $aAid).'"';
    }
}
?>