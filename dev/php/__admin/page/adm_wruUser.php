<?php

include('../__global.php');


class adm_wruUser extends admin_ctrl
{
    private $dataDefine;
    private $model;

    public function _construct()
    {
        
        $this->dataDefine = 'wruUser';
        $this->model = new ml_model_wruUser($this->dataDefine);

        //
    }
    
    protected function output($data)
    {
        $data['_dataDefine'] = $this->dataDefine;
        parent::output($data);
    }

    
    protected function run()
    {
        $page = $this->input('p');
        $pagesize = $this->input('pagesize' , 'g' , 10);
        $this->model->std_listByPage($page , $pagesize);
        $data['rows'] = $this->model->get_data();
        $data['pagesize'] = $pagesize;

        $this->model->std_getCount();
        $data['total'] = $this->model->get_data();

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
    protected function page_showReadedTag(){
        $uid = $this->input('uid');
        $oRds = new ml_model_rdsUserReaded;
        $aTag2Weight= $oRds->getReadedTag($uid , true);
        
        $aTagId = array_keys($aTag2Weight);
        
        $oTag = new ml_model_admin_dbTag;
        $oTag->tags_get_by_taghash($aTagId);

        $aTags = Tool_array::format_2d_array($oTag->get_data() , 'tag_hash' , Tool_array::FORMAT_FIELD2ROW);
        

        foreach ($aTag2Weight as $key => $value) {
            echo $aTags[$key]['tag']."\t\t".$value."\n";
        }
        $data['tagInfo'] = $aTags;
        $data['tagReaded'] = $aTag2Weight;


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
}

new adm_wruUser();
?>
