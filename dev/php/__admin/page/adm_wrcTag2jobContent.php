<?php

include('../__global.php');


class adm_wrcTag2jobContent extends admin_ctrl
{
    private $dataDefine;
    private $model;

    public function _construct()
    {
        
        $this->dataDefine = 'wrcTag2jobContent';
        $this->model = new ml_model_wrcTag2jobContent($this->dataDefine);

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
        $pagesize = $this->input('pagesize','g',10);
        $this->model->std_listByPage($page , $pagesize);
        $data['rows'] = $this->model->get_data();
        foreach ($data['rows'] as $row) {
            $aTagid[] = $row['tagid_1'];
            $aTagid[] = $row['tagid_2'];
            $aJobContentId[] = $row['jobContentId'];
        }

        $oTag = new ml_model_admin_dbTag();
        $oTag->tags_get_by_ids($aTagid);
        $aTag = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);

        $oJobContent = new ml_model_wrcJobContent();
        $oJobContent->get_by_ids($aJobContentId);
        $aJobContent = Tool_array::format_2d_array($oJobContent->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);


        foreach ($data['rows'] as &$row) {
            $row['tag_1'] = $aTag[$row['tagid_1']];
            $row['tag_2'] = $aTag[$row['tagid_2']];
            $row['jobContent'] = $aJobContent[$row['jobContentId']];
        }

        $data['pagesize'] = $pagesize;

        $this->output($data);
    }
    protected function page_addForm()
    {
        $category = $this->input('category');

        $oJobContent = new ml_model_wrcJobContent();
        if($category)
            $oJobContent->get_by_category($category);
        else
            $oJobContent->std_listByPage(1,0);
        $data['aJobContent'] = Tool_array::format_2d_array($oJobContent->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);
        $data['category'] = $category;

        $this->output($data);
    }
    protected function page_editForm()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $data['row'] = $this->model->get_data();


        $oJobContent = new ml_model_wrcJobContent();
        if($category)
            $oJobContent->get_by_category($category);
        else
            $oJobContent->std_listByPage(1,0);
        $data['aJobContent'] = Tool_array::format_2d_array($oJobContent->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);


        $this->output($data);
    }
    
    protected function api_add()
    {
        $category = $this->input('category');
        $jobContentId = $this->input('jobContentId');
        $tag_1 = $this->input('tag_1');
        $tag_2 = $this->input('tag_2');

        $oTag = new ml_model_admin_dbTag();
        $oTag->tags_get_by_tag(array($tag_1 , $tag_2));
        $aTag = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_ID2VALUE);
        if(count($aTag) != 2){
            $this->alert('tag not exits;');
            $this->back();
        }



        $data = array(
            'category' => $category,
            'jobContentId' => $jobContentId,
            'tagid_1' => min(array_keys($aTag)),
            'tagid_2' => max(array_keys($aTag)),
        );

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

new adm_wrcTag2jobContent();
?>
