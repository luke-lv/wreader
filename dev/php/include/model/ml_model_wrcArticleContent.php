<?php
/**
 * 
 */
class ml_model_wrcArticleContent extends Lib_datamodel_db 
{
    const STATUS_NORMAL = 0;
    const STATUS_DEL = 2;

    private $dataDefine;
    function __construct()
    {
        $this->dataDefine = $dataDefine;
        $db_config = ml_factory::load_standard_conf('dbContentbase');        //目前只有一个配置文件，所以


        parent::__construct('wrc_articleContent' , $db_config['wrc_articleContent']);
        $this->_is_ctime = false;
        $this->_is_utime = false;
    }
    
    protected function hash_table($Ym)
    {
        return '_'.$Ym;
    }

    function std_getRowById($id)
    {
        $Ym = $this->_calc_date_by_articleId($id);
        if(!$this->init_db($Ym , self::DB_SLAVE))
            return false;
        $sql = 'select * from '.$this->table.' where id="'.$id.'"';
        return $this->fetch_row($sql);
    }

    function std_addRow($srcId , $articleId , $data = array())
    {
        $Ym = $this->_calc_date_by_articleId($articleId);
        if(!$this->init_db($Ym , self::DB_MASTER))
            return false;

        $data['id'] = $articleId;
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
    function std_realDelById($id)
    {
        $Ym = $this->_calc_date_by_articleId($id);
        if(!$this->init_db($Ym , self::DB_MASTER))
            return false;

        $where = '`id` = "'.$id.'"';
        

        return $this->delete($where);

    }

    private function _calc_date_by_articleId($article_id)
    {
        $date = strtotime(base_convert(substr($article_id , 0 , 5),36,10));
        return date('Ym' , $date);
    }
}
?>