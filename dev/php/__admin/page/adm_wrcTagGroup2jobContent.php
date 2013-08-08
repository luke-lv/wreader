<?php

include('../__global.php');


class adm_wrcTagGroup2jobContent extends admin_ctrl
{
    private $dataDefine;
    private $model;

    public function _construct()
    {
        
        $this->dataDefine = 'wrcTagGroup2jobContent';
        $this->model = new ml_model_wrcTagGroup2jobContent($this->dataDefine);

        //
    }
    
    protected function output($data)
    {
        $data['_dataDefine'] = $this->dataDefine;
        parent::output($data);
    }

    
    protected function run()
    {
        $page = $this->input('p','g',1);
        $pagesize = $this->input('pagesize' , 'g' , 10);
        $category = $this->input('category' , 'g' , 0);
        $this->model->std_getCount($category);
        $data['total']=$this->model->get_data();
        $this->model->std_listByPage($page , $pagesize , $category);
        $data['rows'] = $this->model->get_data();
        foreach ($data['rows'] as $row) {
            $aJobContentId[] = $row['jobContentId'];
            $aContentName_tagid[] = $row['contentName_tagid'];
        }


        $oJc = new ml_model_wrcJobContent();
        $oJc->get_by_ids($aJobContentId);
        $data['aJobContent'] = Tool_array::format_2d_array($oJc->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);

        $oTag = new ml_model_admin_dbTag();
        $oTag->tags_get_by_ids($aContentName_tagid);
        $data['aContentName'] = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);

        $data['pagesize'] = $pagesize;

        $this->output($data);
    }
    protected function page_addForm()
    {
        $category = $this->input('category' , 'g' , ML_TAGCATEGORY_TECH);
        $oJc = new ml_model_wrcJobContent();
        $oJc->get_by_category($category , 1 , 0);
        $data['aJobContent'] = Tool_array::format_2d_array($oJc->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);

        $oTag = new ml_model_admin_dbTag();
        $oTag->tags_list(1,0,$category , false , ML_TAGTYPE_CONTENTNAME);
        $data['aContentName'] = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);

        $this->output($data);
    }
    protected function page_editForm()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $data['row'] = $this->model->get_data();


        
        $oJc = new ml_model_wrcJobContent();
        $oJc->std_getRowById($data['row']['jobContentId']);
        $data['jobContent'] = $oJc->get_data();

        $oTag = new ml_model_admin_dbTag();
        $oTag->get_by_id($data['row']['contentName_tagid']);
        $data['contentName'] = $oTag->get_data();


        $this->output($data);
    }
    
    protected function api_add()
    {
        $category = $this->input('category');
        $jobContentId = $this->input('jobContentId');
        $contentName_tagid = $this->input('contentName_tagid');
        $tags = explode(' ' , $this->input('tags'));



        $data['category'] = $category;
        $data['jobContentId'] = $jobContentId;
        $data['contentName_tagid'] = $contentName_tagid;
        $data['tags'] = $tags;

        $this->model->std_addRow($data);
        $this->back();
    }
    protected function api_edit()
    {
        $id = $this->input('id');
        $tags = explode(' ',$this->input('tags'));
        $data['tags'] = $tags;
        $this->model->std_updateRow($id , $data);
        $this->back();
    }
    protected function api_delById()
    {
        $id = $this->input('id');
        $this->model->std_delById($id);
        $this->back();
    }
}

new adm_wrcTagGroup2jobContent();
?>
