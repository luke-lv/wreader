<?php

include('../__global.php');


class adm_home extends admin_ctrl
{
    function run()
    {
        $this->output();
    }
    
    function page_suggestlist()
    {

        $pageid = $this->input('page');
        $pageid = $pageid < 1 ? 1 : $pageid;
        $pagesize = 20;



        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->suggest_list($pageid , $pagesize);
        $data['suggestlist'] = $oAdmComm->get_data();
        $oAdmComm->suggest_count();
        
        $data['total'] = $oAdmComm->get_data();
        $data['page'] = $pageid;
        $data['pagesize'] = $pagesize;

        $this->output($data);
    }
    function api_suggestreply()
    {
        
        $id=$this->input('id');
        $reply = $this->input('reply');
        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->suggest_reply($id , $reply);

        $this->back();
    }
    
    function page_commentlist()
    {

        $pageid = $this->input('page');
        $pageid = $pageid < 1 ? 1 : $pageid;
        $pagesize = 20;



        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->comment_list($pageid , $pagesize);
        $data['list'] = $oAdmComm->get_data();
        $oAdmComm->suggest_count();
        
        $data['total'] = $oAdmComm->get_data();
        $data['page'] = $pageid;
        $data['pagesize'] = $pagesize;

        $this->output($data);
    }

    function api_notice()
    {
        $notice = $this->input('notice');
        $notice_eng = $this->input('notice_eng');
        $data = array(
            'notice' => $notice,
            'notice_eng' => $notice_eng
        );
        $config_path = K5_CONFIG_PATH.'/notice_data.php';
        helper_filesystem::rewrite_php_return_array_config($config_path , $data);
        
        
        $oUpload = new lib_uploader();
        
        $img_path = K5_DATA_PATH.'/notice.jpg';
        if($oUpload->is_set('pic'))
        {
            $oUpload->start('pic');
            $oUpload->set_file_name('notice.jpg' , false);
            $oUpload->set_save_dir(K5_DATA_PATH);
            $oUpload->save(true);
            
            $oResize = new lib_imageResize();
            $oResize->set_dest_image($img_path);
            $oResize->set_source_image($img_path);
            $oResize->set_max_size(160);
            $oResize->resize();
        }
        
        $this->_redirect('?page=notice');
    }

    function api_codeautomake()
    {
        $dtdfn = $this->input('dtdfn');
        $dataDefine = ml_factory::load_dataDefine($dtdfn);
        //if(is_array($dataDefine) && !is_file('./adm_'.$dtdfn.'.php'))
        //{
            
            $page_code = file_get_contents( SERVER_ROOT_PATH.'/__admin/page/adm_standard.php');
            $page_code = str_replace('adm_standard', 'adm_'.$dtdfn, $page_code);
            $page_code = str_replace('ml_model_standard', 'ml_model_'.$dtdfn, $page_code);
            $page_code = str_replace('$this->input(\'dtdfn\')', '\''.$dtdfn.'\'', $page_code);
            file_put_contents(SERVER_ROOT_PATH.'/__admin/page/adm_'.$dtdfn.'.php', $page_code);

            copy(SERVER_ROOT_PATH.'/__admin/view/page_adm_standard.php', SERVER_ROOT_PATH.'/__admin/view/page_adm_'.$dtdfn.'.php');

            $page_code = file_get_contents( SERVER_ROOT_PATH.'/include/model/ml_model_standard.php');
            $page_code = str_replace('ml_model_standard', 'ml_model_'.$dtdfn, $page_code);
            file_put_contents(SERVER_ROOT_PATH.'/include/model/ml_model_'.$dtdfn.'.php', $page_code);
             
            die('over');
        //}
        //else
        //    die('错误');
    }

    function page_queueStat()
    {
        echo date('H:i:s')."<br/><br/><br/>";

        $oRds = new ml_model_rdsQueue();
        $oRds->listQueue();
        die;
    }
}

new adm_home();
?>