<?php
/**
 * @fileoverview     登录判断
 * @important
 * @author:            shaopu@staff.sina.com
 * @date            Tue Apr 26 08:37:08 GMT 2011
 * @package         博小报
 */



class ml_biz_login
{
    static $_instance;
    private $_obj_sess;
    const SESSION_ONLINE_SIGN_NAME = 'MEILA_LOGIN';
    const ML_WEIBO_ACCESS_TOKEN = '2.00K3ZHvBRb2NnB010ea5824f0jcS5M';

    private function __construct(){
        $this->_obj_sess = ml_controller::getSession();
    }

    public static function get_instance()
    {
        if (is_a(self::$_instance , __CLASS__))
        return $this->_instance;
        else
        return new ml_biz_login();
    }


    /**
     * @param array $userinfo
     $userinfo['uid'];(必选)
     $userinfo['nick'];
     $userinfo['email'];
     $userinfo['status'];
     $userinfo['verify_email'];
     实际上为account表的精简信息
     * 登录session
     * @param $userinfo 数组，来源account，必须包含uid
     * 无uid返回false
     */
    public function ml_login($userinfo) {

        //传入信息无uid，失败
        if (!array_key_exists('uid', $userinfo)) {
            return false;
        }


        
        $oUserJob = new ml_model_wruUserJob();
        $oUserJob->std_getRowById($user['uid']);
        $userinfo['userJob'] = $oUserJob->get_data();
        

         
        ml_tool_ua::add_usid($user['uid']);
         
        $this->_obj_sess->setVal(self::SESSION_ONLINE_SIGN_NAME, $userinfo );
        $this->_obj_sess->save();



        $pt = ml_factory::get_controller();
        $pt->_check_vistor();
    }

    /**
     * 清除session中meila登录信息
     * 清除cookie记住我
     * Enter description here ...
     */
    public function ml_logout() {

        //$this->_obj_sess->unregister( self::SESSION_ONLINE_SIGN_NAME );
        $this->_obj_sess->setVal( self::SESSION_ONLINE_SIGN_NAME , array());
        $this->_obj_sess->save();
        setcookie ("ml_u_id", "", time() - 3600, '/', ML_COOKIE_DOMAIN);
        setcookie ("ml_u_ticket", "", time() - 3600, '/', ML_COOKIE_DOMAIN);

        $pt = ml_factory::get_controller();
        $pt->_check_vistor();

    }



    /**
     * session是否登录
     * Enter description here ...
     */
    public function is_ml_login()
    {

        $userinfo = $this->_obj_sess->getVal(self::SESSION_ONLINE_SIGN_NAME);

        return empty($userinfo['uid'])? false: $userinfo;
    }




    /**
     * 坚持一个uid不改变不动摇
     * 更改登录session信息
     * $newinfo 数组中可选信息nickname,email,verify_email,status
     */
    public function modify_login($newinfo) {
         
        $userinfo = $this->_obj_sess->getVal(self::SESSION_ONLINE_SIGN_NAME );
         
        //我们要保持登录session的纯洁性，切实加强登录session的建设,不畏辛劳,安全第一
        if (isset($newinfo['nick'])) {
            $userinfo['nick'] = $newinfo['nick'];
        }
        if (isset($newinfo['email'])) {
            $userinfo['email'] = $newinfo['email'];
        }
        if (isset($newinfo['verify_email'])) {
            $userinfo['verify_email'] = $newinfo['verify_email'];
        }
        if (isset($newinfo['status'])) {
            $userinfo['status'] = $newinfo['status'];
        }
        if (isset($newinfo['job'])) {
            $userinfo['job'] = $newinfo['job'];
        }

        $this->_obj_sess->setVal(self::SESSION_ONLINE_SIGN_NAME, $userinfo);
        $this->_obj_sess->save();

        $pt = ml_factory::get_controller();
        $pt->_check_vistor();
    }



    public function cookie_remember($uid, $key, $third) {
         
        //$login_ip = Tool_ip::get_real_ip();
        $life = time() + 3600*24*14;             //cookie有效期，两周
        setcookie('ml_u_id', $uid, $life, '/', ML_COOKIE_DOMAIN);
        setcookie('ml_u_ticket', $this->getCookieTicket($uid,$key,$third), $life, '/', ML_COOKIE_DOMAIN);
    }


    /**
     * 通过cookie登录
     * Enter description here ...
     */
    public function cookie_login() {
         
        $ck_uid = $_COOKIE['ml_u_id'];
        $ck_ticket = $_COOKIE['ml_u_ticket'];

        if (empty($ck_uid)|| empty($ck_ticket)) return false;

        $ticket = substr($ck_ticket, 0, 32);
        $ticket_format = substr($ck_ticket, -5);

        if (substr(md5($ck_uid), -5) != $ticket_format) {
            return false;
        }
        
        $third = substr($ck_ticket, 32, 1);
        
        
        if ($third) {
            
            $o3rd = new ml_model_dbUser3rdService();
            $rs = $o3rd->get3rdServiceUser($ck_uid, $third, $data);
            $key = $data['3rd_id'];

            if ($ticket == $this->getRawTicket($key)) {
                $oUser = new ml_model_wruUser();
                $oUser->std_getRowById($ck_uid);
                $rs = $oUser->get_data();
                $this->ml_login($rs);
            }
        } else {
            $oUser = new ml_model_wruUser();
                $oUser->std_getRowById($ck_uid);
                $rs = $oUser->get_data();
            
            $key = $rs['email'];
            if ($ticket == $this->getRawTicket($key)) {
                $this->ml_login($rs);
            }
        }

        $pt = ml_factory::get_controller();
        $pt->_check_vistor();
    }

    
    public function ssoUserInfo( $use_session=false ){
        
        include_once(SERVER_ROOT_PATH.'/Inc/Lib/Sso/Client.class.php');        
        $sso = new SSO_Client();
        if( $use_session ){
            $sso->setConfig( 'use_session', true ); 
        }else{
            $sso->setConfig( 'use_session', false );
        }
        if($sso->isLogined()){
            $ssoUser = $sso->getUserInfo();
            $datawb['3rd_id'] = $ssoUser['uniqueid'];
            $datawb['access_token'] = self::ML_WEIBO_ACCESS_TOKEN;
            
            $openapi = new ml_model_openapiWeibo ();
            $userinfo = $openapi->get_user_info ($datawb);
            
            if (empty($userinfo)) {
                $data['isLogin'] = false;
            } else {
                $data['weiboID'] = $datawb['3rd_id'];
                $data['isLogin'] = $userinfo['gender'] == 'f' ? true: false;
                $data['weiboNick'] = $userinfo['nickname'];
                $data['weiboHeadPic'] = $userinfo['head_url'];
                $data['timeout'] = time() + 60*60*2;
            }
            
            $rs = $this->_obj_sess->setVal('weibo_info', $data);
            $this->_obj_sess->save();
            return true;
        }else{
            $data['isLogin'] = false;
            $rs = $this->_obj_sess->setVal('weibo_info', $data);
            $this->_obj_sess->save();
            return false;
        }
    }
    
    public function getWeiboInfo(){
        $weibo_info = $this->_obj_sess->getVal('weibo_info');
        return $weibo_info;
    }
    
    
    

    ////////////////////内部方法
    private  function getCookieTicket($uid, $key, $third = 0) {
        
        $uidMd5 = md5($uid);
        
        $tmp = $this->getRawTicket($key);
        
        return $tmp.intval($third).substr($uidMd5, -5);
    }
    
    private function getRawTicket($key) {
        
        return md5(md5('evangelion#'.serialize($key) ) );
    }
    
     
}