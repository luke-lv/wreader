<?php
include_once (SERVER_ROOT_PATH . '/Inc/Lib/Openapi/Openapi_weibo.php');
class ml_model_openapiWeibo extends Lib_openapi_abstract {
    /*
     * 检测返回code
     */
    public function check_auth($code) {
        $keys = array ();
        $keys ['code'] = $code;
        $keys ['redirect_uri'] = OPENAPI_CALLBACK_URL;
        $weibo_o = new SaeTOAuthV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN );
        try {
            $token = $weibo_o->getAccessToken ( 'code', $keys );
        } catch ( OAuthException $e ) {
            
        }
        $session = ml_controller::getSession ();
        $session->setVal ( 'access_token', $token ['access_token'] );
        $session->setVal ( '3rd_id', $token ['uid'] );
        $session->getVal ( 'access_token' );
        $session->save ();
        if ($token)
            return $token;
        else
            return false;
    }
    
    /*
     * 获取授权用链接
     */
    public function get_auth_url() {
        $weibo_o = new SaeTOAuthV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN );
        $auth_url = $weibo_o->getAuthorizeURL ( OPENAPI_CALLBACK_URL, 'code', 'sina' );
        return $auth_url;
    }
    
    
    /**
     * 获取手机授权用链接
     */
    public function get_wap_auth_url() {
        $weibo_o = new SaeTOAuthV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN );
        $auth_url = $weibo_o->getAuthorizeURL ( WAP_OPENAPI_CALLBACK_URL,'code', 'sina' );
        return $auth_url;
    }
    
    
    /*
     * 获取用户基本信息
     */
    public function get_user_info($data = null) {
        
        if (empty($data)){
            $session = ml_controller::getSession ();
            $access_token = $session->getVal ( 'access_token' );
            $third_id = $session->getVal ( '3rd_id' );
        } else {
            $access_token = $data['access_token'];
            $third_id = $data['3rd_id'];
        }
        
        $weibo_c = new SaeTClientV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN, $access_token );
        $return = $weibo_c->show_user_by_id ( $third_id );
        if (! $return ['error_code']) {
            $userinfo = array ();
            $userinfo ['nickname'] = $return ['name'];
            $userinfo ['gender'] = $return ['gender'];
            $userinfo ['birthday'] = '';
            /* $userinfo ['province_id'] = $return ['province'];
            $userinfo ['city_id'] = $return ['city']; */
            $userinfo ['head_url'] = $return ['avatar_large'].'.jpg';
            if ( preg_match ( '/\/0\/0$/', $return ['avatar_large'] )) {
                $userinfo ['is_default_headico'] = true;
            } else {
                $userinfo ['is_default_headico'] = false;
            }
            return $userinfo;
        } else
            return false;
    }
    
    /*
     * (non-PHPdoc) @see Lib_openapi_abstract::sent_message()
     */
    public function sent_message($data) {
        // TODO Auto-generated method stub
        if(!isset($data['access_token'])){
            $session = ml_controller::getSession ();
            $data['access_token'] = $session->getVal ( 'access_token' );
        }
        $weibo_c = new SaeTClientV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN, $data['access_token'] );
        
        if(empty($data['picurl'])){
            $rs=$weibo_c->update($data['content']);        
        }else{
            $rs=$weibo_c->upload($data['content'],$data['picurl']);    
        }
        return $rs;
    }

    /**
     * weibo关注
     * @param $data
     * @return bool
     */
    public function follow_uid($data){

        if(empty($data['access_token'])){
            return false;
        }
        $weibo_c = new SaeTClientV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN, $data['access_token'] );
        $rs=$weibo_c->follow_by_id($data['wb_id']);
        if(isset($re['id'])){
            return true;
        }else{
            return false;
        }
    }

    public function is_followed($data){
        if(empty($data['access_token'])){
            return false;
        }
        $weibo_c = new SaeTClientV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN, $data['access_token'] );
        $re=$weibo_c->is_followed_by_id($data['wb_id']);
        if($re['target']['following']){
            return true;
        }else{
            return false;
        }
    }

    public function get_user_follows_ids($data) {
        
        if (empty($data)){
            $session = ml_controller::getSession ();
            $access_token = $session->getVal ( 'access_token' );
            $third_id = $session->getVal ( '3rd_id' );
        } else {
            $access_token = $data['access_token'];
            $third_id = $data['3rd_id'];
        }
        
        $weibo_c = new SaeTClientV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN, $access_token );
        $return = $weibo_c->friends_ids_by_id($third_id);
        if (! $return ['error_code']) {
            $userinfo = $return;
            return $userinfo;
        } else
            return false;
    }
    
    public function get_user_fans_ids($data) {
        
        if (empty($data)){
            $session = ml_controller::getSession ();
            $access_token = $session->getVal ( 'access_token' );
            $third_id = $session->getVal ( '3rd_id' );
        } else {
            $access_token = $data['access_token'];
            $third_id = $data['3rd_id'];
        }
        
        $weibo_c = new SaeTClientV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN, $access_token );
        $return = $weibo_c->followers_ids_by_id($third_id);
        if (! $return ['error_code']) {
            $userinfo = $return;
            return $userinfo;
        } else
            return false;
    }
    
    public function get_user_follows($data, $page = 0, $num = 50) {
        
        if (empty($data)){
            $session = ml_controller::getSession ();
            $access_token = $session->getVal ( 'access_token' );
            $third_id = $session->getVal ( '3rd_id' );
        } else {
            $access_token = $data['access_token'];
            $third_id = $data['3rd_id'];
        }
        
        $weibo_c = new SaeTClientV2 ( OPENAPI_WEIBO_APP_KEY, OPENAPI_WEIBO_APP_TOKEN, $access_token );
        $return = $weibo_c->friends_by_id($third_id, $page, $num);
        if (! $return ['error_code']) {
            $userinfo = $return;
            return $userinfo;
        } else
            return false;
    }

}
