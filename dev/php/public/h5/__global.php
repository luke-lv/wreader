<?php
include (dirname(dirname(dirname(__FILE__))).'/__global.php');

if(empty($_COOKIE['uid']))
{
	setcookie('uid' , 1);
	$_COOKIE['uid'] = 1;
}


class wr_h5mobileController extends ml_controller
{
	public function init(){
		$this->set_tpl_dir(dirname(__FILE__).'/_template');
		$this->set_uri_dir('/public/h5/');

		$this->__visitor['uid'] = $_COOKIE['uid'];


		$aJobsConf = ml_factory::load_standard_conf('wreader_jobs');
		$aJobid2Info = array();
		foreach ($aJobsConf as $row) {
			$aJobid2Info = $aJobid2Info + $row['jobs'];
		}
		


		$oUser = new ml_model_wruUser();
		$oUser->std_getRowById($this->__visitor['uid']);
		$this->__visitor = $oUser->get_data();



		$oUserJob = new ml_model_wruUserJob();
		$oUserJob->std_getRowById($this->__visitor['job']);
$this->__visitor['job'] = $oUserJob->get_data();

		$jobInfo = $aJobid2Info[$this->__visitor['job']['job_id']];

		
		$this->add_output_data('jobInfo' , $jobInfo);
		$this->add_output_data('levelInfo' , ml_factory::load_standard_conf('wreader_jobLevel'));

	}



}