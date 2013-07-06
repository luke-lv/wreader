<?php
	include(dirname(__FILE__).'/__global.php');



	class wrh5m_settingPerson extends wr_h5mobileController
	{
		private $oBizSuggArt;
		public function main()
		{

			/*
			$aJobsConf = ml_factory::load_standard_conf('wreader_jobs');
			$aJobid2Info = array();
			foreach ($aJobsConf as $row) {
				$aJobid2Info = $aJobid2Info + $row['jobs'];
			}
			


			$oUser = new ml_model_wruUser();
			$oUser->std_getRowById($this->__visitor['uid']);
			$userInfo = $oUser->get_data();


			$oUserJob = new ml_model_wruUserJob();
			$oUserJob->std_getRowById($userInfo['job']);
			$userJobInfo = $oUserJob->get_data();

			$jobInfo = $aJobid2Info[$userJobInfo['job_id']];

			$data = array(
				'userInfo' => $userInfo,
				'userJobInfo' => $userJobInfo,
				'jobInfo' => $jobInfo,
				'levelInfo' => ml_factory::load_standard_conf('wreader_jobLevel'),
			);
 */
			//var_dump($data);
			

			$this->page_output('settingPerson' , $data);
		}
	}

	new wrh5m_settingPerson();