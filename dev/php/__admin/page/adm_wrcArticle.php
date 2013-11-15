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
        $aJobContentId = array();


        $oSrc = new ml_model_wrcSource();
        $oSrc->std_getRowById($srcId);
        $aSrc = $oSrc->get_data();
        $category = $aSrc['category'];

        $oJobContent = new ml_model_wrcJobContent();
        $oJobContent->get_by_category($category , 1 , 0);
        $data['aJobContent'] = Tool_array::format_2d_array($oJobContent->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);

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

        $oSrc = new ml_model_wrcSource();
        $oSrc->std_getRowById($data['row']['source_id']);
        $aSource = $oSrc->get_data();

        $ojc = new ml_model_wrcJobContent();
        $ojc->get_by_category($aSource['category']);
        $data['aJobContent'] = Tool_array::format_2d_array($ojc->get_data() , 'name' , Tool_array::FORMAT_ID2VALUE);
        $this->output($data);
    }
    protected function page_articleShow()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $data['articleRow'] = $this->model->get_data();
        
        $this->modelContent->std_getRowById($id);

        $data['articleRow'] = array_merge($data['articleRow'] , $this->modelContent->get_data());
        
        $oSource = new ml_model_wrcSource;
        $oSource->std_getRowById($data['articleRow']['source_id']);
        $data['source'] = $oSource->get_data();
        $data['ym'] = ml_model_wrcArticle::_calc_Ym_by_articleId($id);

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
    protected function api_realDelById()
    {
        $id = $this->input('id');
        $this->model->std_realDelById($id);
        $this->modelContent->std_realDelById($id);
        $this->back();
    }
    protected function api_changeJobContentIdById()
    {
        $id = $this->input('id');
        $jobContentId = $this->input('jobContentId');
        $this->model->std_updateRow($id , array('jobContentId' => array($jobContentId)));
        $this->back();
    }
    protected function api_reSegment()
    {
        $id = $this->input('id');
        $this->model->std_getRowById($id);
        $articleInfo = $this->model->get_data();

        $oSource = new ml_model_wrcSource();
        $oSource->std_getRowById($articleInfo['source_id']);
        $sourceInfo = $oSource->get_data();


        $aTag = ml_function_lib::segmentChinese($articleInfo['title']);
        array_filter($aTag);
        $aTag = array_merge($aTag , $sourceInfo['tags']);


        $oBiz = new ml_biz_articleTag2jobContent();
        $aJobContentId = $oBiz->execute($aTag);
        $dataUpdate['tags'] = $oBiz->getMetaTag();
        if(!empty($aJobContentId))
        {
            $dataUpdate['jobContentId'] = $aJobContentId;
        }

        $this->model->std_updateRow($id , $dataUpdate);

        $oBizA2r = new ml_biz_articleid2redis();
        $oBizA2r->execute($id , $dataUpdate['tags'] , $dataUpdate['jobContentId']);


        // $this->modelContent->std_getRowById($id);
        // $aContent = $this->modelContent->get_data();
        // $aTag = ml_tool_chineseSegment::segmentWithAttr($aContent['content']);
        // foreach ($aTag as $key => $tagInfo) {
        //     if($tagInfo['idf'] >0){
        //         $aTagAvail[] = $tagInfo['word'];
        //     }
        // }
        // $aTagCount = array_count_values($aTagAvail);
        // arsort($aTagCount);
        // var_dump($aTagCount);
        // die;
        $this->back();
    }
    protected function api_seg2wordgroup()
    {
        $id = $this->input('id');
        $this->modelContent->std_getRowById($id);
        $articleContent = $this->modelContent->get_data();

        $oBizw2wg = new ml_biz_contentParse_word2wordgroup();
        $oBizw2wg->execute_2($articleContent['content']);
        die;

        $content = ml_tool_chineseSegment::filterUnavailableStr($articleContent['content']);
        $aWordInfo = ml_tool_chineseSegment::segmentWithAttr($content , false);
        foreach ($aWordInfo as $wordInfo) {
            $aWord[] = $wordInfo['word'];

            $aWord2Info[$wordInfo['word']] = $wordInfo;
        }
        
        $aWordCnt = array_count_values($aWord);
        arsort($aWordCnt);
        foreach ($aWordCnt as $key => $value) {
            
            if($aWord2Info[$key]['attr'] != 'en' && $aWord2Info[$key]['idf']>4.2 && $aWord2Info[$key]['idf']<5){
                echo '-------------';
            }
            echo $key.' '.$value.' '.$aWord2Info[$key]['idf'].' '.$aWord2Info[$key]['attr']."<br/>";
        }
        
        $oBizw2wg = new ml_biz_contentParse_word2wordgroup();
        var_dump($oBizw2wg->execute($content));

    }
}

new adm_wrcArticle();
?>
