<?php

include ('__global.php');
include (SERVER_ROOT_PATH . '/include/config/dataRule/ml_datarule_user.php');
header ( 'Content-Type: text/html; charset=utf-8' );
class call_back_controller extends wr_h5mobileController {
    
    protected $__need_login = false;
    protected $__need_job = false;
    private $type = NULL;
    
    private $server_id = NULL;
    
    private $code = NULL;
    
    public function initParam() {
        $this->type = $this->input ( 'type', 'G' );
        $this->type = $this->type ? $this->type : $this->input ( 'state', 'G' );
        //var_dump($_GET);die();
    }
    
    public function checkParam() {
    
    }
    
    public function main() {

Tool_logger::debugLog('callback','start');
        // 初始化第三方登录session
        $session = $this->getSession ();
        $prev_url=$session->getVal ( 'prev_url');
        $session->setVal('prev_url','');
        // 根据授权类型实例化api类
        if ($this->type == 'tencent') {
            $session->setVal ( 'server_id', 2 );
            $session->save ();
            $this->server_id = 2;
            $openapi = new ml_model_openapiQq ();
            $this->code = $this->input ( 'oauth_verifier', 'G' );
        } elseif ($this->type == 'renren') {
            $session->setVal ( 'server_id', 3 );
            $session->save ();
            $this->server_id = 3;
            $openapi = new ml_model_openapiRenren ();
            $this->code = $this->input ( 'code', 'G' );
        } elseif ($this->type == 'sina') {
            $session->setVal ( 'server_id', 1 );
            $session->save ();
            $this->server_id = 1;
            $openapi = new ml_model_openapiWeibo ();
            $this->code = $this->input ( 'code', 'G' );
        } elseif ($this->type == 'kaixin') {
            $session->setVal ( 'server_id', 4 );
            $session->save ();
            $this->server_id = 4;
            $openapi = new ml_model_openapiKaixin ();
            $this->code = $this->input ( 'oauth_verifier', 'G' );
        } elseif ($this->type == 'taobao' ) {
            $session->setVal ( 'server_id', 5 );
            $session->save ();
            $this->server_id = 5;
            $openapi = new ml_model_openapiTaobao();
            $this->code = $this->input ( 'top_parameters', 'G' );

            //var_dump($this->code);
        } else {
            // 参数错误 授权失败
            $this->systemError('登录参数错误');
        }

        $token = $openapi->check_auth ( $this->code );

        
        if ($token) {
            $DB_3rd = new ml_model_dbUser3rdService ();
            // 判断是否登录
            if ($this->check_permission(ML_PERMISSION_LOGIN_ONLY)) {

                Tool_logger::debugLog('callback','logined');
                // 已经登录
                // 判断当前用户的当前授权类型是否已经存在
                $isExists = NULL;

                if ($DB_3rd->is3rdServiceType ( $this->__visitor ['uid'], $this->server_id, &$isExists )) {

                    if ($isExists) {
                        Tool_logger::debugLog('callback','update_token');
                        ml_tool_sendMq::update_token($this->__visitor ['uid'], $this->server_id , $token['access_token']);//更新token


                        if(!empty($prev_url) ){
                            $this->redirect ($prev_url);
                        }else{
                            $this->redirect ( wrh5m_urlMaker::settingPerson() );
                        }
                    } else {
                        Tool_logger::debugLog('callback','login');
                        $data = NULL;
                        $third_id = $session->getVal ( '3rd_id' );
                        if(!empty($third_id)){
                            $return = $DB_3rd->is3rdServiceUser ( $this->server_id, $third_id, &$data );
                        }
                        if (empty($third_id)  ||  !$return  ) {
                            $this->systemError('系统繁忙 第三方登录验证失败');
                        }
                        if (count ( $data ) > 0) {
                            // 登录成功
                            $this->loginProxy(ml_logout);
                            Tool_logger::debugLog('callback','loginsuc');

                            $oUser = new ml_model_wruUser();

                            $regdata = array(
                                'nick' => $useacconut['nick']
                            );
                            $oUser->std_getRowById($data['uid']);
                            $useacconut = $oUser->get_data();

                            //ml_tool_actionlog::login($data ['uid'] , 1 , $third_id);
                            $this->loginProxy(ml_login, $useacconut );
                            
                            //ml_tool_sendMq::update_token($data ['uid'],$this->server_id,$token['access_token']);
                            
                            
                            if(!empty($prev_url) ){
                                $this->redirect ($prev_url);
                            }else{
                                $this->redirect ( wrh5m_urlMaker::suggestArticle() );
                            }
                        } else {
                            // 添加第三方授权信息
                            Tool_logger::debugLog('callback','append3rd');
                            $return = $DB_3rd->create3rdService ( $this->__visitor ['uid'], $this->server_id, $session->getVal ( 'access_token' ), $session->getVal ( 'token_secret' ), $session->getVal ( '3rd_id' ) );
                            if ($return) {
                                if(!empty($prev_url) ){
                                    $this->redirect ($prev_url);
                                }else{
                                    $this->redirect (wrh5m_urlMaker::settingPerson() );
                                }
                            } else {
                                // 系统繁忙
                                $this->systemError('第三方绑定失败');
                            }
                        }
                    }
                } else {
                    $this->systemError('系统繁忙 第三方绑定检测失败');
                }
            } else {
                // 未登录
                // 判断授权id是否存在,存在直接登录
                Tool_logger::debugLog('callback','nologin');
                $data = NULL;
                $third_id = $session->getVal ( '3rd_id' );

                if(!empty($third_id)){
                    $return = $DB_3rd->is3rdServiceUser ( $this->server_id, $third_id, &$data );
                    if (!$return) {
                        $this->systemError('系统繁忙 第三方登录验证失败');
                    }
                }


                if (count ( $data ) > 0) {
                    // 登录成功
                    Tool_logger::debugLog('callback','3rdlogin');
                    $oUser = new ml_model_wruUser();
                    $oUser->std_getRowById($data['uid']);
                    $useacconut = $oUser->get_data();
                    $this->loginProxy(ml_login, $useacconut );
                    $this->_login->cookie_remember($userinfo ['uid'], $third_id, $this->server_id);
                    
                    //ml_tool_sendMq::update_token($data['uid'], $this->server_id , $token['access_token']);//更新token

                    if(!empty($prev_url) ){
                        $this->redirect ($prev_url);
                    }else{
                        $this->redirect ( wrh5m_urlMaker::suggestArticle() );
                    }
                } else {
                    // 通过接口获取和数据
                    Tool_logger::debugLog('callback','nologin startreg');
                    $userinfo = $openapi->get_user_info ();
                    
                    $session->setVal ( 'userinfo', $userinfo );
                    $session->save ();
                    //echo '<0';var_dump($userinfo);die();
                    if (! $userinfo) {
                        $this->systemError('获取用户信息失败');
                    }
                    
                    
                    // 昵称检测通过 自动注册流程
                    $regdata = array(
                        'nick' => $userinfo['nickname'],
                    );
                    $oUser = new ml_model_wruUser();
                    $oUser->std_addRow($regdata);

                    
                    $uid = $oUser->insert_id ();
                    

                    /*
                    //行为日志
                    ml_tool_actionlog::reg_3rd($uid , $this->server_id , $userinfo ['nickname'] , $userinfo ['nickname'] , ($userinfo ['gender']=='f' ? ML_DATARULE_USER_GENDER_GIRL : ML_DATARULE_USER_GENDER_BOY));
                    
                    //投昵称队列
                    $arr = array('uid'=>$uid,'nick'=>$userinfo ['nickname']);
                    ml_tool_sendMq::update_nick($arr);
                    */

                    $return = $DB_3rd->create3rdService ( $uid, $this->server_id, $session->getVal ( 'access_token' ), $session->getVal ( 'token_secret' ), $session->getVal ( '3rd_id' ) );
                    if (! $return) {
                        $this->systemError('系统繁忙2');
                    }
                    
                    $userinfo_1 ['uid'] = $uid;
                    $userinfo_1 ['status'] = ML_USERSTATUS_THIRD;
                    $userinfo_1 ['nick'] = $userinfo ['nickname'];
                    

                    
                    $this->loginProxy(ml_login, $userinfo_1 );
                    $this->_login->cookie_remember($uid, $third_id, $this->server_id);
                    $userinfo = $session->getVal ( 'userinfo' );
                    /*
                    if (! $userinfo ['is_default_headico']) {
                        $headuploadurl = OPENAPI_HEAD_UPLOAD . '?imgurl=' . urldecode ( $userinfo ['head_url'] ) . '&uid=' . $uid;
                        Tool_http::get ( $headuploadurl );
                    }
                     */
                    
                    if(!empty($prev_url) ){
                        $this->redirect ($prev_url);
                    }else{
                        $this->redirect(wrh5m_urlMaker::suggestArticle());
                        //$this->redirect ( SITE_ROOT_URL . '/page/relation/recommend.php' );
                    }
                        
                    
                }
            }
        } else {
            // 授权失败
            $this->systemError('授权失败');
        }
    
    }
}
new call_back_controller ();
?>