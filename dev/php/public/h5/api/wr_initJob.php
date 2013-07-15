<?php
	include(dirname(dirname(__FILE__)).'/__global.php');

	class wr_initJob extends wr_h5mobileAPIController
	{
		protected $__need_login = true;

		private $_job_id;

		public function checkParam(){
			$this->_job_id = (int)$this->input('job_id');
		}

		public function main()
		{
			$oUserJob = new ml_model_wruUserJob();

			$data = array(
				'uid' => $this->__visitor['uid'],
				'job_id' => $this->_job_id,
			);
			$rs = $oUserJob->std_addRow($data);
			if(!$rs)
				$this->api_output(WR_APICODE_SYSTEM);

			$jobRowId = $oUserJob->insert_id();

			$oUser = new ml_model_wruUser();
			$data = array(
				'job' => $jobRowId,
			);
			$rs = $oUser->std_updateRow($this->__visitor['uid'] , $data);
			if(!$rs){
				$this->api_output(WR_APICODE_SYSTEM);
			}

			$this->loginProxy('modify_login' , array('job' => $jobRowId));

			$this->api_output(WR_APICODE_SUCCESS);
		}
	}

	new wr_initJob();