<?php
include(dirname(dirname(__FILE__)).'/__global.php');

class wrhp_selectJob extends wr_h5phoneController
{
	public function main()
	{
		$jobsConf = ml_factory::load_standard_conf('wreader_jobs');
		
		$this->page_output('selectJob',array());
	}
}
new wrhp_selectJob();