<?php
include (dirname(dirname(dirname(__FILE__))).'/__global.php');

class wr_h5phoneController extends ml_controller
{
	public function init(){
		$this->set_tpl_dir(dirname(__FILE__).'/_template');
		$this->set_uri_dir('/public/h5/');
	}

	public function main()
	{
		$jobsConf = ml_factory::load_standard_conf('wreader_jobs');
		
		$this->page_output('xx',array());
	}
}