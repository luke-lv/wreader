<?php

include('../__global.php');


class adm_tags extends admin_ctrl
{
    private $idfList = array(
        1,0.9,0.8,0.7
    );

    function run()
    {

        $page = $this->input('p','all',1);
        $category = $this->input('category','all',0);
        $tag = $this->input('tag','all',0);
        $type = $this->input('type','all',0);
        $is_core = $this->input('is_core' , 'all' , 0);

            $oAdmComm = new ml_model_admin_dbTag();
        if($tag)
        {
            $aTag = explode(',', $tag);
            if(count($aTag) == 1)
            {
                $oAdmComm->tags_get_by_tag_like($aTag[0]);
            }
            else
            {
                $oAdmComm->tags_get_by_tag($aTag);
            }
            $data['tags'] = $oAdmComm->get_data();
            $category = $data['tags'][0]['category'];
        }
        else
        {
            
            $oAdmComm->tags_list($page,50,$category , $is_core , $type);
            $data['tags'] = $oAdmComm->get_data();

            $oAdmComm->tags_count($category , $is_core , $type);
            $data['total'] = $oAdmComm->get_data();
            
            $data['page'] = $page;
        }


        $contentNameTag = $oAdmComm->core_tag_get_by_type_category($category , ML_TAGTYPE_CONTENTNAME);
        $data['contentNameTag'] = Tool_array::format_2d_array($oAdmComm->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);
        $contentNameTag = $oAdmComm->core_tag_get_by_type_category($category , ML_TAGTYPE_CONTENTTYPE);
        $data['contentTypeTag'] = Tool_array::format_2d_array($oAdmComm->get_data(), 'tag' , Tool_array::FORMAT_ID2VALUE);
        
        $oJc = new ml_model_wrcJobContent();
        $oJc->get_by_category($category, 1, 0);

        $data['jobContent'] = Tool_array::format_2d_array($oJc->get_data(), 'name' , Tool_array::FORMAT_ID2VALUE);

        $oAdmComm->core_tags_get_all($category);
        $aCoreTag = $oAdmComm->get_data();
        $oAdmComm->core_tags_get_all(ML_TAGCATEGORY_CONTENTTYPE);
        $aCoreTag = array_merge($aCoreTag , $oAdmComm->get_data());
        $aCoreTag = Tool_array::format_2d_array($aCoreTag , 'tag' , Tool_array::FORMAT_ID2VALUE);
        $aCoreTag[0] = '无';
        ksort($aCoreTag);
        $data['coreTag'] = $aCoreTag;
        $data['category'] = $category;
        $data['idfList'] = $this->idfList;


        
        $this->output($data);
    }
    
    function page_nearHotTag()
    {
        $oArticle = new ml_model_wrcArticle();
        $oArticle->getLatestArticle();
        $rows = $oArticle->get_data();
        $aAllTag = array();
        foreach ($rows as $key => $value) {
            $aAllTag = array_merge($aAllTag, $value['tags']);

        }
        
        $aAllTag = array_count_values($aAllTag);
        arsort($aAllTag);


        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_get_by_tag(array_keys($aAllTag));
        $data['tags'] = $oAdmComm->get_data();

        $oAdmComm->core_tags_get_all();
        $aCoreTag = Tool_array::format_2d_array($oAdmComm->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);
        $aCoreTag[0] = '无';
        $data['coreTag'] = $aCoreTag;
        

        $this->output($data , 'index');
    }
    function page_redisTagStat()
    {
        $oRdsCB = new ml_model_rdsContentBase();
        $aTag2cnt = $oRdsCB->listAllTags();
        
        if(empty($aTag2cnt))
            die('none1');

        $oTag = new ml_model_admin_dbTag();

        $oTag->tags_get_by_taghash(array_keys($aTag2cnt));
        $aTags = $oTag->get_data();
        if(empty($aTags))
            die('none2');

        foreach ($aTags as $key => &$value) {
            $value['article_cnt'] = (int)$aTag2cnt[$value['tag_hash']];
        }
        $data['tags'] = $aTags;
        
        return $this->output($data);
    }
    function page_addForm()
    {
        $category = (int)$this->input('category');
        $data['category'] = $category;
        $oTag = new ml_model_admin_dbTag();
        $oTag->tags_list(1 , 0 , $category , true , ML_TAGTYPE_CONTENTNAME);
        $data['aCnTag'] = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);

        $oTag->tags_list(1 , 0 , $category , true , ML_TAGTYPE_CONTENTTYPE);
        $data['aCtTag'] = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);


        $this->output($data);
    }


    function api_batch_add()
    {
        $tags = explode("\n", $this->input('tags'));
        foreach ($tags as &$value) {
            $value = trim($value);
        }
        $category = $this->input('category');
        $contentName_tagid = $this->input('contentName_tagid');
        $contentType_tagid = $this->input('contentType_tagid');


        $oAdmComm = new ml_model_admin_dbTag();
        $data['contentName_tagid'] = $contentName_tagid;
        $data['contentType_tagid'] = $contentType_tagid;
        $oAdmComm->tags_batch_add($category , $tags , $data);
        $this->back();
    }

    function api_changeTypeById()
    {
        $id = $this->input('id');
        $type = $this->input('type');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_type_by_id($type , $id);

        $this->back('#id'.$id);
    }

    function api_changeIdfById()
    {
        $id = $this->input('id');
        $idf= $this->input('idf');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_idf_by_id($idf , $id);

        $this->back('#id'.$id);
    }



    function api_changeJobContentIdById()
    {
        $id = $this->input('id');
        $jcid = $this->input('jobContentId');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_jobContentId_by_id($jcid , $id);

        $this->back('#id'.$id);
    }
    function api_changeContentNameById()
    {
        $id = $this->input('id');
        $tag_id = $this->input('tag_id');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_content_name_by_id($tag_id , $id);

        $this->back('#id'.$id);
    }
    function api_changeContentTypeById()
    {
        $id = $this->input('id');
        $tag_id = $this->input('tag_id');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_content_type_by_id($tag_id , $id);

        $this->back('#id'.$id);
    }
    function api_changeLevelById()
    {
        $id = $this->input('id');
        $level = $this->input('level');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_level_by_id($level , $id);

        $this->back('#id'.$id);
    }
    function api_changecategoryById()
    {
        $id = $this->input('id');
        $category = $this->input('category');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_category_by_id($category , $id);

        $this->back('#id'.$id);
    }
    function api_changeCoreTagidById()
    {
        $id = $this->input('id');
        $coreTagid = $this->input('coreTagid');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_core_tagid_by_id($coreTagid , $id);

        $this->back('#id'.$id);
    }
    function api_changeIsCoreById()
    {
        $id = $this->input('id');
        $v = $this->input('value');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_is_core_by_id($v , $id);

        $this->back('#id'.$id);
    }
    function api_changePtById()
    {
        $id = $this->input('id');
        $pt = $this->input('pt');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_change_pt_by_id($pt , $id);

        $this->back('#id'.$id);
    }
    function api_delByIds()
    {
        $ids = $_POST['ids'];
        $oTag = new ml_model_admin_dbTag();
        $oTag->tags_del_by_ids($ids);
        $this->back();
    }

    function api_delTag()
    {
        $id = $this->input('id');
        $oAdmComm = new ml_model_admin_dbTag();
        $oAdmComm->tags_del($id);
        $this->back();
    }

}

new adm_tags();
?>