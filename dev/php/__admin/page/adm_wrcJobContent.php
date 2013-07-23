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
        $page = $this->input('page','g',0);
        $pagesize = $this->input('pagesize','g',10);
        $job_id = $this->input('job_id','job_id',0);
        $this->model->std_listByPage($job_id , $page , $pagesize);
        $data['rows'] = $this->model->get_data();

        foreach ($data['rows'] as $row) {
            $aTagHash[] = $row['contentType_tagHash'];
            $aTagHash[] = $row['contentName_tagHash'];
        }
        $oTag = new ml_model_admin_dbTag();
        $oTag->tags_get_by_taghash($aTagHash);

        $aTag = Tool_array::format_2d_array($oTag->get_data() , 'tag_hash' , Tool_array::FORMAT_VALUE2VALUE2 , 'tag');


        foreach ($data['rows'] as &$row) {
            $row['contentType'] = $aTag[$row['contentType_tagHash']];
            $row['contentName'] = $aTag[$row['contentName_tagHash']];
        }

        $data['pagesize'] = $pagesize;

        $this->output($data);
    }
    protected function page_try()
    {
        $this->output();
    }
    protected function page_addForm()
    {
     
        $job_id = $this->input('job_id');
        $level = $this->input('level');


        $jobConf = ml_factory::load_standard_conf('wreader_jobs');
        foreach ($jobConf as $jobType) {
            if(isset($jobType['jobs'][$job_id]))
                $tag_category = $jobType['tag_category'];
        }

        $oTag = new ml_model_admin_dbTag();
        $oTag->core_tag_get_by_type_category($tag_category , ML_TAGTYPE_CONTENTNAME);
        $data['contentName'] = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_VALUE2VALUE2 , 'tag_hash');
        

        if($job_id){
            $this->model->listByJobidLevel($job_id , $level);
            $data['jobContent'] = Tool_array::format_2d_array($this->model->get_data() , 'contentName_tagHash' , Tool_array::FORMAT_FIELD2ROW);

        }


        $data['job_id'] = $job_id;       
        $data['level'] = $level;       
        $this->output($data);
    }
    protected function page_editForm()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $data['row'] = $this->model->get_data();
        $this->output($data);
    }
    
    protected function api_add()
    {
        $job_id = $this->input('job_id');
        $contentNames = $_POST['contentName'];
        
        $level = $_POST['level'];
        $recommend_level = $_POST['recommend_level'];
        foreach ($contentNames as $tag_hash) {
            $data['job_id'] = $job_id;
            $data['contentName_tagHash'] = $tag_hash;
            $data['recommend_level'] = $recommend_level[$tag_hash];
            $data['level'] = $recommend_level[$tag_hash];
            $this->model->std_addRow($data);    
        }



        
        $this->back();
    }
    protected function api_edit()
    {
        $dataDefine = ml_factory::load_dataDefine($this->dataDefine);

        $id = $this->input('id');
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

    protected function api_coreTagList()
    {
        $category = $this->input('category');
        $oTag = new ml_model_admin_dbTag();
        $oTag->core_tag_get_by_type_category( $category , ML_TAGTYPE_CONTENTNAME);
        $data['contentName'] = addslashes( json_encode(array('aaaa','aaww','bbbb','cc','eefef')));
        $data['cn_cnt'] = 5;
        //json_encode(Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_VALUE_ONLY));

        $oTag->core_tag_get_by_type_category( $category , ML_TAGTYPE_CONTENTTYPE);
        $data['contentType'] = json_encode(Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_VALUE_ONLY));

        $this->output_js(ML_RCODE_SUCC , $data);
    }
}

new adm_wrcJobContent();
?>
