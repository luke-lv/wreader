<?php
	include(dirname(__FILE__).'/__global.php');



	class wrh5m_noLogin extends wr_h5mobileController
	{
		protected $__need_login = false;
		protected $__need_job = false;
		public function main()
		{
			$data = array();
			$this->page_output('noLogin' , $data);
		}
	}

	new wrh5m_noLogin();