<?php
	include(dirname(dirname(__FILE__)).'/__global.php');

	class wr_setAttendTag extends wr_h5mobileAPIController
	{

		private $_tags;

		public function checkParam(){
			$this->_tags = (int)$this->input('tags');
		}

		public function main()
		{
			$oUserJob = new ml_model_wruUserJob();
			$data['attend_tags'] = explode(' ' , $this->_tags);
			$oUserJob->std_updateRow($this->__visitor['job'] , $data);


			$this->api_output(WR_APICODE_SUCCESS);
		}
	}

	new wr_setAttendTag();