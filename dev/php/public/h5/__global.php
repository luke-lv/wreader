<?php
include (dirname(dirname(dirname(__FILE__))).'/__global.php');

if(empty($_COOKIE['uid']))
{
	setcookie('uid' , 1);
	$_COOKIE['uid'] = 1;
}
ml_factory::load_standard_conf('userSetting');

define('WR_APICODE_SUCCESS' , '00001');
define('WR_APICODE_LOGIN' , '00008');
define('WR_APICODE_SYSTEM' , '00009');


class wr_h5mobileController extends ml_controller
{
	protected $__need_login = true;
	protected $__need_job = true;

	public function init(){
		$this->set_tpl_dir(dirname(__FILE__).'/_template');
		$this->set_uri_dir('/public/h5/');


		if($this->__need_login)
		{
			if(!$this->check_permission(ML_PERMISSION_LOGIN_ONLY)){

				$this->redirect(wrh5m_urlMaker::noLogin());
			}
			else if($this->__need_job && empty($this->__visitor['userJob']))
			{
				$this->redirect(wrh5m_urlMaker::initJob());
			}

		}

		
		if($this->__visitor['uid'])
		{
			$oUserSet = new ml_model_wruUserSetting();
			
			$oUserSet->getByUidType($this->__visitor['uid'] , ML_USERSET_HTML5MOB_THEME);
			$setRow = $oUserSet->get_data();
			$this->__visitor['h5mTheme'] = $setRow['data'];
			
			$this->add_output_data('jobInfo' , $jobInfo);
			$this->add_output_data('levelInfo' , ml_factory::load_standard_conf('wreader_jobLevel'));
		}
	}

	public function systemError($msg)
	{
		$session = $this->getSession();
		$session->setVal ( 'err_msg', '系统繁忙 第三方登录验证失败' );
        $session->save ();
        $this->redirect ( wrh5m_urlMaker::systemError() );
	}
}

class wr_h5mobileApiController extends ml_controller
{
	protected $__need_login = true;


	public function init(){


		if($this->__need_login)
		{
			if(!$this->check_permission(ML_PERMISSION_LOGIN_ONLY)){

				$this->api_output(WR_APICODE_LOGIN);
			}

		}

		
	}

}

class wrh5m_urlMaker
{
	static public function settingPerson()
	{
		return 'wrh5m_settingPerson.php';
	}
	static public function settingSet()
	{
		return 'wrh5m_settingSet.php';
	}
	static public function noLogin()
	{
		return 'nologin.php';
	}
	static public function initJob()
	{
		return 'wrh5m_initJob.php';
	}
	static public function suggestArticle()
	{
		return 'wrh5m_suggestArticle.php';
	}
	static public function systemError()
	{
		return 'system_error.php';
	}
}