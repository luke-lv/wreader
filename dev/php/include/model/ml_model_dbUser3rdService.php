<?php

/**
 * 第三方授权类型定义server_id
 * 1:新浪微薄
 * 2:腾讯微薄
 * 3:人人网
 */
class ml_model_dbUser3rdService extends Lib_datamodel_db {
    
    public function __construct() {
        $db_config = ml_factory::load_standard_conf ( 'dbUser' );
        parent::__construct ( 'wru_user3rdService', $db_config ['wru_user3rdService'] );
    }
    
    /**
     * 根据授权类型、授权ID和授权token返回用户uid
     *
     * @param $type int           
     * @param $oauth_token string           
     * @param $oauth_token_secret string           
     */
    public function is3rdServiceUser($server_id, $_3rd_id, &$date) {
        if (! $this->init_db ()){
            return false;
        }
        $where = 'SELECT *  FROM ' . $this->table . " WHERE `service_id` = '" . $server_id . "' and `3rd_id`='" . $_3rd_id . "'";
        $rs = $this->fetch_row ( $where );
        if (! $rs){
            return false;
        }
        $date = $this->_data;
        return true;
    }
    
    public function get3rdServiceUser($uid, $server_id, &$date) {
        if (! $this->init_db ())
            return false;
        $where = 'SELECT *  FROM ' . $this->table . " WHERE `uid` = " . $uid . " and `service_id`= " . $server_id ;
        $rs = $this->fetch_row ( $where );
        if (! $rs)
            return false;
        $date = $this->_data;
        return true;
    }
    
    
    /*
     * 根据uid,授权类型 判断用户第三方授权是否已经存在
     */
    public function is3rdServiceType($uid, $type, &$isExists) {
        if (! $this->init_db ())
            return false;
        $where = "`uid` = '" . $uid . "' and `service_id`='" . $type . "'";
        $rs = $this->fetch_count ( $where );
        if (! $rs)
            return false;
        $isExists = $this->_data > 0 ? true : false;
        return true;
    }
    public function setSync($uid, $type, $sync) {
        if (! $this->init_db ())
            return false;
        
        $sql = "UPDATE `" . $this->table . "` SET `msg_sync` ='" . $sync . "'  WHERE `uid` ='" . $uid . "' and `sercice_id`='" . $type . "'";
        $rs = $this->query ( $sql );
        return $rs ? $rs : false;
    }
    /*
     * 根据uid,返回用户已有的第三方授权类型
     */
    public function get3rdServiceType($uid, &$date) {
        if (! $this->init_db ())
            return false;
        $where = 'SELECT *  FROM ' . $this->table . " WHERE `uid` = '" . $uid . "'";
        $rs = $this->fetch ( $where );
        if (! $rs)
            return false;
        $date = $this->_data;
        return true;
    }
    /*
     * 添加第三方授权信息
     */
    public function create3rdService($uid, $service_id, $access_token, $token_secret, $_3rd_id) {
        if (! $this->init_db ())
            return false;
        $arrInsert = array (
                'uid' => $uid, 
                'service_id' => $service_id, 
                'access_token' => $access_token, 
                'token_secret' => $token_secret, 
                '3rd_id' => $_3rd_id 
        );
        
        return $this->insert ( $arrInsert );
    }
    
    /**
     * 修改access_token
     * @gaojian3
     * @param unknown_type $uid
     * @param unknown_type $service_id
     * @param unknown_type $access_token
     * @return unknown
     */
    public function updateAccessToken($uid,$service_id,$access_token){
        if (! $this->init_db ())
            return false;
            
        $updateArr['access_token'] = $access_token;
        $where=' `uid` = '.$this->escape($uid).'  AND  `service_id` = '.$this->escape($service_id);
        
        return $this->update($updateArr,$where);
    }
}
?>