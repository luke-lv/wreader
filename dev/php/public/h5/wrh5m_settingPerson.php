<?php
	include(dirname(__FILE__).'/__global.php');



	class wrh5m_settingPerson extends wr_h5mobileController
	{
		public function main()
		{
			$jobInfo = ml_tool_jobs::getJobConf($this->__visitor['userJob']['job_id']);

			$data['jobInfo'] = $jobInfo;
			$this->page_output('settingPerson' , $data);
		}
	}

	new wrh5m_settingPerson();