<?php
include (dirname(dirname(dirname(__FILE__))).'/__global.php');

class wr_h5mobileController extends ml_controller
{
	public function init(){
		$this->set_tpl_dir(dirname(__FILE__).'/_template');
		$this->set_uri_dir('/public/h5/');
	}


}