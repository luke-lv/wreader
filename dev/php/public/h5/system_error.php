<?php
	include(dirname(__FILE__).'/__global.php');



	class wrh5m_systemError extends wr_h5mobileController
	{
		private $oBizSuggArt;
		public function main()
		{
			$session = $this->getSession();

			$data['err_msg'] = $session->getVal('err_msg');
			$this->page_output('systemError' , $data);
		}
	}

	new wrh5m_systemError();