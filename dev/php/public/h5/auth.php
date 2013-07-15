<?php
include ('__global.php');

class auth_url_controller extends ml_controller {
    private $type = NULL;
    
    public function initParam() {
        $this->type = $this->input ( 'type', 'G' );
    }
    public function checkParam() {
    
    }
    public function main() {
        $session = $this->getSession ();
        $session->setVal ( 'access_token', '' );
        $session->setVal ( 'token_secret', '' );
        $session->setVal ( '3rd_id', '' );
        $session->setVal ( 'access_token_key', '' );
        $session->setVal ( 'userinfo', '' );
        $session->save ();
        
        
        if ($this->type == 'tencent') {
            $openapi = new ml_model_openapiQq ();
            $url = $openapi->get_auth_url ();
        } elseif ($this->type == 'renren') {
            $openapi = new ml_model_openapiRenren ();
            $url = $openapi->get_auth_url ();
        } elseif ($this->type == 'sina') {
            $openapi = new ml_model_openapiWeibo ();
            $url = $openapi->get_auth_url ();
        } elseif ($this->type == 'kaixin') {
            $openapi = new ml_model_openapiKaixin ();
            $url = $openapi->get_auth_url ();
        }elseif($this->type == 'taobao') {
            $openapi = new ml_model_openapiTaobao();
            $url = $openapi->get_auth_url ();
        }else{
            $url = SITE_ROOT_URL;
        }
        
        $this->redirect ( $url );
    }
}
new auth_url_controller ();
?>