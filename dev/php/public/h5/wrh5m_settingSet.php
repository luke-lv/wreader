<?php
	include(dirname(__FILE__).'/__global.php');



	class wrh5m_settingSet extends wr_h5mobileController
	{
		private $oBizSuggArt;
		public function main()
		{
			$this->page_output('settingSet' , $data);
		}
	}

	new wrh5m_settingSet();