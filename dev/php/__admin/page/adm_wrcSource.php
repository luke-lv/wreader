<?php

include('../__global.php');


class adm_wrcSource extends admin_ctrl
{
    private $dataDefine;
    private $model;

    public function _construct()
    {
        
        $this->dataDefine = 'wrcSource';
        $this->model = new ml_model_wrcSource($this->dataDefine);

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
        $this->model->std_listByPage($page , $pagesize , 1);
        $data['rows'] = $this->model->get_data();
        $this->model->std_getCount();
        $data['total'] = $this->model->get_data();
        $data['pagesize'] = $pagesize;
        $this->output($data);
    }
    protected function page_addForm()
    {
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
        $dataDefine = ml_factory::load_dataDefine($this->dataDefine);

        foreach ($dataDefine['field'] as $key => $value) {
            $data[$key] = $this->input($key);
        }
        
        if(!$data['domain'] && $data['rss'])
        {
            $rs = parse_url($data['rss']);
            $data['domain'] = $rs['host'];
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

        if(!$data['domain'] && $data['rss'])
        {
            $rs = parse_url($data['rss']);
            $data['domain'] = $rs['host'];
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
    protected function api_setStatusById()
    {
        $id = $this->input('id');
        $status = $this->input('status');
        
        $this->model->std_setStatusById($id , $status);
        $this->back();
    }
    protected function api_reRedis()
    {
        $srcId = $this->input('id');

        $oBizA2R = new ml_biz_articleid2redis();

        //get all article
        $oArticle = new ml_model_wrcArticle();
        $oArticle->std_listBySrcIdByPage($srcId , 0 , 1 , 0);
        $aRows = $oArticle->get_data();

        if (!empty($aRows)) {
            foreach ($aRows as $key => $value) {
                $oBizA2R->execute($value['id'] , $value['tags']);
            }
        }
        
        $this->_redirect($_SERVER['HTTP_REFERER'] , '重建完成' , 1);    
    }
    //protected function 
}

new adm_wrcSource();
?>
