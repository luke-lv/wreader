<?php
	include(dirname(dirname(__FILE__)).'/__global.php');

	class wr_setAttendTag extends wr_h5mobileAPIController
	{

		private $_tags;

		public function checkParam(){

			$this->_tags = str_replace("&nbsp;" , '' ,$this->input('tags'));
		}

		public function main()
		{
			$oUserJob = new ml_model_wruUserJob();

			$data = array();
			$data['attend_tag'] = explode(' ' , $this->_tags);

			$oUserJob->std_updateRow($this->__visitor['uid'] , $data);


			$this->api_output(WR_APICODE_SUCCESS);
		}
	}

	new wr_setAttendTag();