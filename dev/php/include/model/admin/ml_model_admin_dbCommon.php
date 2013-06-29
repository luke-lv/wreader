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

class ml_model_admin_dbCommon extends Lib_datamodel_db
{
    const TB_SUGGEST = 'mla_suggestlist';
    const TB_COMMENT = 'mla_commentlist';
    const TB_TAGS = 'wrc_tags';
    const TB_HTMLBLOCK = 'mla_htmlblock';
    const TB_GOODSAUTOFETCH = 'mla_goodsautofetch';

    public function __construct()
    {
        $db_config = ml_factory::load_standard_conf('dbContentbase');


        parent::__construct('wrc_source' , $db_config['wrc_source']);
    }

    public function suggest_list($pageid , $pagesize = 20)
    {
        if(!$this->init_db())
            return false;


        $start = ($pageid-1)*$pagesize;
        $sql = 'select * from '.self::TB_SUGGEST.' order by ctime desc limit '.$start.','.$pagesize;
        

        return $this->fetch($sql);
    }
    public function suggest_count($pageid)
    {
        if(!$this->init_db())
            return false;

        $this->table = self::TB_SUGGEST;
        return $this->fetch_count();
    }
    public function suggest_add($uid , $content , $page_url)
    {
        if(!$this->init_db())
            return false;

        $arr = array(
            'content' => $content,
            'cip' => Tool_ip::get_real_ip(),
            'page_url' => $page_url
        );
        $this->table = self::TB_SUGGEST;
        return $this->insert($arr);
    }
    public function suggest_reply($id , $content)
    {
        if(!$this->init_db())
            return false;

        $arr = array(
            'adm_reply' => $content,
            'rtime' => date()
        );
        $where = 'id='.$id;
        $this->table = self::TB_SUGGEST;
        return $this->update($arr , $where);
    }


    public function comment_add($uid , $content , $rid)
    {
        if(!$this->init_db())
            return false;

        $arr = array(
            'uid' => $uid,
            'content' => $content,
            'rid' => $rid,
        );
        $this->table = self::TB_COMMENT.'_'.date('Ym');
        return $this->insert($arr);
    }

    public function comment_list($pageid , $pagesize = 20)
    {
        if(!$this->init_db())
            return false;


        $start = ($pageid-1)*$pagesize;
        $sql = 'select * from '.self::TB_COMMENT.'_'.date('Ym').' order by ctime desc limit '.$start.','.$pagesize;
        

        return $this->fetch($sql);
    
    }
    public function comment_count($pageid)
    {
        if(!$this->init_db())
            return false;

        $this->table = self::TB_COMMENT.'_'.date('Ym');
        return $this->fetch_count();
    }

    public function autofetch_add($url ,$iid, $tag , $class = 0)
    {
        if(!$this->init_db())
            return false;    

        $arr = array(
            'url' => $url,
            'num_iid' => $iid,
            'tag' => $tag,
            'class' => $class,
        );

        $this->table = self::TB_GOODSAUTOFETCH;
        return $this->insert($arr);
    }

    public function autofetch_getbyid($id)
    {
        if(!$this->init_db())
            return false;

        $this->table = self::TB_GOODSAUTOFETCH;

        $sql = 'select * from '.$this->table.' where id='.$id;
        $rs = $this->fetch_row($sql);

    }

    public function autofetch_pass($id , $status = 1)
    {
        if(!$this->init_db())
            return false;

        $data = array('status' => $status);
        $this->table = self::TB_GOODSAUTOFETCH;

        return $this->update($data , 'id='.$id);
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
            $arrTags[$k] = $this->escape($tag);
        }
        $sTag = '"'.implode('","' , $arrTags).'"';
        
        $sql = 'select * from '.self::TB_TAGS.' where `tag` in ('.$sTag.')';
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


    public function htmlblock_list($type , $page , $pagesize = 20)
    {
        if(!$this->init_db())
            return false;

        $start = ($page-1)*$pagesize;
        if($type>0)
            $where = ' where type='.$type;
        $sql = 'select * from '.self::TB_HTMLBLOCK.$where.' limit '.$start.','.$pagesize;
        

        return $this->fetch($sql);
    }
    public function htmlblock_getbyid($id)
    {
        if(!$this->init_db())
            return false;


            $where = ' where id='.$id;
        $sql = 'select * from '.self::TB_HTMLBLOCK.$where;
        
        return $this->fetch_row($sql);
    }
    public function htmlblock_add($name , $content , $page , $comment)
    {
        if(!$this->init_db())
            return false;    

        $arr = array(
            'name' => $name,
            'content' => $content,
            'comment' => $comment,
            'page' => $page,
        );

        $this->table = self::TB_HTMLBLOCK;
        return $this->insert($arr);

    }
    public function htmlblock_update($id , $name , $content , $page , $comment)
    {
        if(!$this->init_db())
            return false;    

        $arr = array(
            'name' => $name,
            'content' => $content,
            'comment' => $comment,
            'page' => $page,
        );

        $this->table = self::TB_HTMLBLOCK;
        $where = 'id='.$id;
        return $this->update($arr , $where);
    }

}