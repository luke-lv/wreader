<?php
	include(dirname(__FILE__).'/__global.php');



	class wrh5m_initJob extends wr_h5mobileController
	{
		protected $__need_job = false;
		
		public function main()
		{

			$data['jobConf'] = ml_factory::load_standard_conf('wreader_jobs');

			$this->page_output('initJob' , $data);
		}
	}

	new wrh5m_initJob();