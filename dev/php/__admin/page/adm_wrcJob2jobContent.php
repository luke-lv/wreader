<?php

include('../__global.php');


class adm_wrcJob2jobContent extends admin_ctrl
{
    private $dataDefine;
    private $model;


    public function _construct()
    {
        
        $this->dataDefine = 'wrcJob2jobContent';
        $this->model = new ml_model_wrcJob2jobContent($this->dataDefine);

        //
    }
    
    protected function output($data)
    {
        $data['_dataDefine'] = $this->dataDefine;
        parent::output($data);
    }

    
    protected function run()
    {
        $page = $this->input('page','g',1);
        $pagesize = $this->input('pagesize' , 'g' , 10);
        $this->model->std_listByPage($page , $pagesize);
        $data['rows'] = $this->model->get_data();
        $data['pagesize'] = $pagesize;
        $this->model->std_getCount();
        $data['total'] = $this->model->get_data();

        $aJobContentId = array();
        foreach ($data['rows'] as $row) {
            $aJobContentId = array_merge($aJobContentId , array_keys($row['jobContentIds']));
        }
        $oJobContent = new ml_model_wrcJobContent();
        $oJobContent->get_by_ids(array_unique($aJobContentId));
        $data['aJobContent'] = Tool_array::format_2d_array($oJobContent->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);

        $this->output($data);
    }
    protected function page_addForm()
    {
        $category = array_filter(explode(',', $this->input('category')));
        $job_id = $this->input('job_id');

        $oJobContent = new ml_model_wrcJobContent();
        $oJobContent->get_by_categorys($category);
        $data['aJobContent'] = Tool_array::format_2d_array($oJobContent->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);
        $data['category'] = $category;
        $data['job_id'] = $job_id;

        $this->output($data);
    }
    protected function page_editForm()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $data['row'] = $this->model->get_data();

        $category = $data['row']['category'];
        if($this->input('category'))
            $category = array_filter(explode(',', $this->input('category')));
        

        $oJobContent = new ml_model_wrcJobContent();
        $oJobContent->get_by_categorys($category);
        $data['aJobContent'] = Tool_array::format_2d_array($oJobContent->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);
        $data['category'] = $category;

        $this->output($data);
    }
    
    protected function api_add()
    {
        $job_id = $this->input('job_id');
        $jobContentId = $_POST['jobContentId'];
        $category = $_POST['category'];

        $data['job_id'] = $job_id;
        $data['level'] = $level;
        $data['category'] = $category;
        $jobContentIds = $jobContentId;
        foreach ($jobContentIds as $jcid) {
            $data['jobContentIds'][$jcid] = array(
                'rcmdLv' => $_POST['recommendlevel'][$jcid],
            );
        }

        $this->model->std_addRow($data);
        $this->back();
    }
    protected function api_edit()
    {
        $id = $this->input('id');
        $job_id = $this->input('job_id');
        $jobContentId = $_POST['jobContentId'];
        $category = $_POST['category'];

        $data['job_id'] = $job_id;
        $data['category'] = $category;
        $jobContentIds = $jobContentId;
        foreach ($jobContentIds as $jcid) {
            $data['jobContentIds'][$jcid] = array(
                'rcmdLv' => $_POST['recommendlevel'][$jcid],
            );
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

new adm_wrcJob2jobContent();
?>
