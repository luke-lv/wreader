<?php

include('../__global.php');


class adm_wrcJobContent extends admin_ctrl
{
    private $dataDefine;
    private $model;

    public function _construct()
    {
        
        $this->dataDefine = 'wrcJobContent';
        $this->model = new ml_model_wrcJobContent($this->dataDefine);

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
        $category = $this->input('category' , 'g' , 1);
        $contentName_tagid = $this->input('contentName_tagid' , 'g' , 0);

        $this->model->count_by_category($category , $contentName_tagid);
        $data['total'] = $this->model->get_data();
        $this->model->get_by_category($category,$page , $pagesize , $contentName_tagid);
        $data['rows'] = $this->model->get_data();
        $data['pagesize'] = $pagesize;
        $data['category'] = $category;

        foreach ($data['rows'] as $row) {
            $aTagid[] = $row['contentType_tagid'];
            $aTagid[] = $row['contentName_tagid'];
        }

        $this->model->get_contentName_by_category($category);
        $aCnTagid = Tool_array::format_2d_array($this->model->get_data() , 'contentName_tagid' ,Tool_array::FORMAT_VALUE_ONLY);


        $oTag = new ml_model_admin_dbTag();
        $oTag->tags_get_by_ids($aTagid);
        $aTag = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);

        foreach ($data['rows'] as &$row) {
            $row['contentType'] = $aTag[$row['contentType_tagid']];
            $row['contentName'] = $aTag[$row['contentName_tagid']];
        }

        $oTag->tags_get_by_ids($aCnTagid);
        $data['aCnTag'] = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);


        $this->output($data);
    }
    protected function page_addForm()
    {
    
        $category = $this->input('category');

        $level = $this->input('level');

        $oTag = new ml_model_admin_dbTag();
        
        $oTag->tags_list(1 , 0 , ML_TAGCATEGORY_CONTENTTYPE , true , ML_TAGTYPE_CONTENTTYPE);
        $aCTtag = $oTag->get_data();
        $oTag->tags_list(1 , 0 , $category , true , ML_TAGTYPE_CONTENTTYPE);
        $aCTtag = array_merge($aCTtag , $oTag->get_data());

        $data['contentType'] = Tool_array::format_2d_array($aCTtag , 'tag' , Tool_array::FORMAT_ID2VALUE);
        
        $oTag->tags_list(1, 0 , $category , true , ML_TAGTYPE_CONTENTNAME);
        $aCNtag = $oTag->get_data();
        $data['contentName'] = Tool_array::format_2d_array($aCNtag , 'tag' , Tool_array::FORMAT_ID2VALUE);

        $data['job_id'] = $job_id;
        $data['category'] = $category;
        $data['level'] = $level;

        $this->output($data);
    }
    protected function page_editForm()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $data['row'] = $this->model->get_data();
        $data['jobConf'] = ml_tool_jobs::getJobConf($data['row']['job_id']);
        $category = $jobConf['category'];
        $oTag = new ml_model_admin_dbTag();


        
        $oTag->tags_list(1 , 0 , ML_TAGCATEGORY_CONTENTTYPE , true , ML_TAGTYPE_CONTENTTYPE);
        $aCTtag = $oTag->get_data();
        $oTag->tags_list(1 , 0 , $category , true , ML_TAGTYPE_CONTENTTYPE);
        $aCTtag = array_merge($aCTtag , $oTag->get_data());

        $data['contentType'] = Tool_array::format_2d_array($aCTtag , 'tag' , Tool_array::FORMAT_ID2VALUE);
        
        $oTag->tags_list(1, 0 , $category , true , ML_TAGTYPE_CONTENTNAME);
        $aCNtag = $oTag->get_data();
        $data['contentName'] = Tool_array::format_2d_array($aCNtag , 'tag' , Tool_array::FORMAT_ID2VALUE);


        $this->output($data);
    }
    
    protected function page_redisStat()
    {
        $oRdsCb = new ml_model_rdsContentBase();
        $aJcid2n = $oRdsCb->listAllJc();

        $oJobContent = new ml_model_wrcJobContent();
        $oJobContent->get_by_ids(array_keys($aJcid2n));
        $aJc = $oJobContent->get_data();
        foreach ($aJc as $row) {
            echo $row['name'].' == '.$aJcid2n[$row['id']]."<br/>";
        }
        die;
    }

    protected function api_add()
    {
        $dataDefine = ml_factory::load_dataDefine($this->dataDefine);

        foreach ($dataDefine['field'] as $key => $value) {
            $data[$key] = $this->input($key);
        }
        $this->model->std_addRow($data);
        $this->back();
    }
    protected function api_edit()
    {
        $dataDefine = ml_factory::load_dataDefine($this->dataDefine);

        $id = $this->input('id');
        unset($dataDefine['field']['job_id']);
        foreach ($dataDefine['field'] as $key => $value) {
            $data[$key] = $this->input($key);
        }
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

new adm_wrcJobContent();
?>
