<?php
/**
 * @copyright meila.com
 * @author shaopu@
 * @name
 * @param
 *         $xxx = 作用
 * @static
 *         XXX = 作用
 *
 *
 */

class ml_model_admin_dbTag extends Lib_datamodel_db
{
    const TB_TAGS = 'wrc_tags';
    public function __construct()
    {
        $db_config = ml_factory::load_standard_conf('dbContentbase');


        parent::__construct('wrc_source' , $db_config['wrc_source']);
    }
    public function tags_list($page , $pagesize = 20 , $type=0)
    {
        if(!$this->init_db())
            return false;

        $start = ($page-1)*$pagesize;
        $where = ' where type='.$type;
        $sql = 'select * from '.self::TB_TAGS.$where.' order by id desc limit '.$start.','.$pagesize;
        

        return $this->fetch($sql);
    }
    public function tags_count($type=0)
    {
        if(!$this->init_db())
            return false;

        $start = ($pageid-1)*$pagesize;
            $where = 'type='.$type;
        
        $this->table = self::TB_TAGS;

        return $this->fetch_count($where);
    }


    public function tags_batch_add($type , $core_tagid , $aTags)
    {
        if(!$this->init_db())
            return false;

        foreach ($aTags as $value) {
            $a = array(
                'type' => $type,
                'core_tagid' => $core_tagid,
                'tag' => $value,
                'tag_hash' => crc32($value),
            );
            $this->table = self::TB_TAGS;
            $this->insert($a);
        }
        return;
    }

    public function tags_change_type_by_id($type , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('type'=>$type) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_change_pt_by_id($pt , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('suggest_pt'=>$pt) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_change_core_tagid_by_id($core_tagid , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('core_tagid'=>$core_tagid) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_change_is_core_by_id($is_core , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('is_core'=>$is_core) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_change_sub_type_by_id($type , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('sub_type'=>$type) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_get_by_tag($arrTags)
    {
        if(!$this->init_db())
            return false;

        
        foreach ($arrTags as $k => $tag)
        {
            $arrTags[$k] = $this->escape(strtolower(trim($tag)));
        }
        $sTag = '"'.implode('","' , $arrTags).'"';
        
        $sql = 'select * from '.self::TB_TAGS.' where `tag` in ('.$sTag.')';
        return $this->fetch($sql);
    }
    public function tags_get_by_taghash($arrTagHash)
    {
        if(!$this->init_db())
            return false;

        foreach ($arrTagHash as $k => $tag)
        {
            $arrTags[$k] = (int)$tag;
        }
        $sTag = implode(',' , $arrTags);
        
        $sql = 'select * from '.self::TB_TAGS.' where `tag_hash` in ('.$sTag.')';
        return $this->fetch($sql);
    }
    public function tags_get_by_ids($aId)
    {
        if(!$this->init_db())
            return false;
        if(empty($aId))
            return false;


        $sql = 'select * from '.self::TB_TAGS.' where `id` in ('.implode(',', $aId).')';
        return $this->fetch($sql);
    }
    public function tags_get_by_tag_like($tag)
    {
        if(!$this->init_db())
            return false;

        $sql = 'select * from '.self::TB_TAGS.' where `tag` like "%'.$tag.'%"';
        return $this->fetch($sql);
    }
    public function core_tags_get_all($type = 0)
    {
        if(!$this->init_db())
            return false;
        $condition = $type > 0 ? ' and type = '.$type : '';
        $sql = 'select * from '.self::TB_TAGS.' where is_core = 1'.$condition;
        return $this->fetch($sql);
    }
    public function core_tag_get_by_tags($aTags)
    {
        $aCoreTag = array();

        $this->tags_get_by_tag($aTags);
        $aTagRow = $this->get_data();

        foreach ($aTagRow as $value) {

            if($value['is_core']==1){
                $aCoreTag[] = $value;
            }
            if($value['core_tagid']){
                $aTagId[] = $value['core_tagid'];
            }
        }

        if(!empty($aTagId))
        {
            $this->tags_get_by_ids($aTagId);
            $aCoreTag = array_merge($aCoreTag , $this->get_data());
        }
        $aCoreTag = Tool_array::format_2d_array($aCoreTag , 'id' , Tool_array::FORMAT_FIELD2ROW);
        $this->set_data($aCoreTag);
        return true;
    }
    public function tags_del($id)
    {
        if(!$this->init_db())
            return false;

        $sql = 'delete from '.self::TB_TAGS.' where `id` = '.$id;
        return $this->query($sql);
    }
    public function tags_getAll()
    {
        if(!$this->init_db())
            return false;

        $sql = 'select * from '.self::TB_TAGS;
        return $this->fetch($sql);
    }


}