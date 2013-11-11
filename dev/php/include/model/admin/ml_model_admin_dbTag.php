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
    public function tags_list($page , $pagesize = 20 , $category=0 , $is_core = false,$type = 0)
    {
        if(!$this->init_db())
            return false;

        $start = ($page-1)*$pagesize;
        $where = ' where 1 ';
        if($category)
            $where .= ' and category='.$category;
        if($is_core)
            $where .= ' and is_core = 1';
        if($type)
            $where .= ' and type = '.$type;

        $limit = '';
        if($pagesize > 0){
            $limit =  ' limit '.$start.','.$pagesize;
        }
        $sql = 'select * from '.self::TB_TAGS.$where.' order by id desc'.$limit;

        return $this->fetch($sql);
    }
    public function tags_count($category=0 , $is_core = 0 , $type = 0)
    {
        if(!$this->init_db())
            return false;

        $start = ($pageid-1)*$pagesize;
        $where = ' 1 ';
        if($category)
            $where .= ' and category='.$category;
        if($is_core)
            $where .= ' and is_core = 1';
        if($type)
            $where .= ' and type = '.$type;

        $this->table = self::TB_TAGS;

        return $this->fetch_count($where);
    }



    public function tags_batch_add($category , $aTags , $data)
    {
        if(!$this->init_db())
            return false;

        $arr = array();
        if($data['contentName_tagid'])
            $arr['contentName_tagid'] = $data['contentName_tagid'];
        if($data['contentType_tagid'])
            $arr['contentType_tagid'] = $data['contentType_tagid'];
        if($data['type'])
            $arr['type'] = $data['type'];
        if($data['core_tagid'])
            $arr['core_tagid'] = $data['core_tagid'];

        foreach ($aTags as $value) {
            $a = array(
                'category' => $category,
                'tag' => $value,
                'tag_hash' => crc32($value),
            );
            $array = array_merge($a , $arr);

            $this->table = self::TB_TAGS;
            $this->insert($array);
        }
        return;
    }

    public function tags_change_category_by_id($category , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('category'=>$category) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_change_level_by_id($level , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('level'=>$level) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_change_jobContentId_by_id($jobContentId , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('jobContentId'=>$jobContentId) , '`id`='.$id , 1);
        
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
    public function tags_change_type_by_id($type , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('type'=>$type) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_change_idf_by_id($idf , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('segment_idf'=>$idf) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_change_content_name_by_id($tagid , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('contentName_tagid'=>$tagid) , '`id`='.$id , 1);
        
        return;
    }
    public function tags_change_content_type_by_id($tagid , $id)
    {
        if(!$this->init_db())
            return false;

            $this->table = self::TB_TAGS;
            $this->update(array('contentType_tagid'=>$tagid) , '`id`='.$id , 1);
        
        return;
    }
    public function get_by_id($id){
        if(!$this->init_db())
            return false;
        $sql = 'select * from '.self::TB_TAGS.' where id='.$id;
        return $this->fetch_row($sql);
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
    public function core_tags_get_all($category = 0)
    {
        if(!$this->init_db())
            return false;
        $condition = $category > 0 ? ' and category = '.$category : '';
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
    public function core_tag_get_by_type_category($category = 0 , $type = 0)
    {
        if(!$this->init_db())
            return false;
        
        
        $where = ' and type = '.$type;
        if($category)
            $where .= ' and category in (0,'.$category.')';
        $sql = 'select * from '.self::TB_TAGS.' where is_core = 1'.$where;

        return $this->fetch($sql);
    }
    public function tags_del($id)
    {
        if(!$this->init_db())
            return false;

        $sql = 'delete from '.self::TB_TAGS.' where `id` = '.$id;
        return $this->query($sql);
    }
    public function tags_del_by_ids($aId)
    {
        if(!$this->init_db())
            return false;

        $sql = 'delete from '.self::TB_TAGS.' where `id` in ('.implode(',', $aId).')';
        return $this->query($sql);
    }
    public function tags_getAll()
    {
        if(!$this->init_db())
            return false;

        $sql = 'select * from '.self::TB_TAGS;
        return $this->fetch($sql);
    }
    public function tags_getAllRelation($aTags)
    {
        $this->tags_get_by_tag($aTags);
        $rows = $this->get_data();
        foreach ($rows as $row) {
            if($row['contentType_tagid'])
                $aExtTagid[] = $row['contentType_tagid'];
            if($row['contentName_tagid'])
                $aExtTagid[] = $row['contentName_tagid'];
            if($row['core_tagid'])
                $aExtTagid[] = $row['core_tagid'];
        }
        $aExtTagid = array_unique(array_filter($aExtTagid));

        $this->tags_get_by_ids($aExtTagid);
        $rows = array_merge($rows , $this->get_data());
        $rows = Tool_array::format_2d_array($rows , 'id' , Tool_array::FORMAT_FIELD2ROW);
        $this->set_data($rows);
        return true;
    }

    static public function tag_hash($tag)
    {
        return crc32($tag);
    }

}