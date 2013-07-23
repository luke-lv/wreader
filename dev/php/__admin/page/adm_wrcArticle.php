<?php

include('../__global.php');


class adm_wrcArticle extends admin_ctrl
{
    private $dataDefine;
    private $model;
    private $modelContent;

    public function _construct()
    {
        
        $this->dataDefine = 'wrcArticle';
        $this->model = new ml_model_wrcArticle($this->dataDefine);
        $this->modelContent = new ml_model_wrcArticleContent();
        //
    }
    
    protected function output($data)
    {
        $data['_dataDefine'] = $this->dataDefine;
        parent::output($data);
    }

    
    protected function run()
    {
        $Ym = $this->input('ym');
        $page = $this->input('p','g',1);
        $pagesize = $this->input('pagesize' , 'g' , 10);
        $srcId = $this->input('srcId');
        if (!$srcId) {
            $this->_redirect('adm_wrcSource.php');
        }
        $this->model->std_listBySrcIdByPage($srcId ,$Ym, $page , $pagesize);
        $data['rows'] = $this->model->get_data();
        $this->model->std_getCountBySrcId($srcId , $Ym);
        $data['total'] = $this->model->get_data();
        $data['pagesize'] = $pagesize;
        $data['srcId'] = $srcId;

        $this->output($data);
    }
    protected function page_addForm()
    {
        $this->output();
    }
    protected function page_editForm()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $data['row'] = $this->model->get_data();
        $this->output($data);
    }
    protected function page_articleShow()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $data['articleRow'] = $this->model->get_data();
        
        $this->modelContent->std_getRowById($id);

        $data['articleRow'] = array_merge($data['articleRow'] , $this->modelContent->get_data());
        
        $this->output($data);
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
    protected function api_reSegment()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $articleInfo = $this->model->get_data();

        $aTag = ml_function_lib::segmentChinese($articleInfo['title']);

        $oTag = new ml_model_admin_dbTag();
        $oTag->tags_get_by_tag($aTag);
        $aTag = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_VALUE_ONLY);

        $this->model->std_updateRow($id , array('tags' => $aTag));
        $this->back();
    }
}

new adm_wrcArticle();
?>
