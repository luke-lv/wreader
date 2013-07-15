<?php
	include(dirname(dirname(__FILE__)).'/__global.php');

	class wr_setTheme extends wr_h5mobileAPIController
	{

		private $_theme;
		private $_fontsize;

		public function checkParam(){
			$this->_theme = $this->input('theme');
			$this->_fontsize = $this->input('fontsize');
		}

		public function main()
		{
			$oUserSet = new ml_model_wruUserSetting();
			$oUserSet->getByUidType($this->__visitor['uid'] , ML_USERSET_HTML5MOB_THEME);
			$data = $oUserSet->get_data();

			$config = $data['data'];

			if($this->_theme)
				$config['theme'] = $this->_theme == 'ori' ? '' : $this->_theme;
			if($this->_fontsize)
				$config['font-size'] = $this->_fontsize;

			$oUserSet->setByUidType($this->__visitor['uid'] , ML_USERSET_HTML5MOB_THEME , $config);

			$this->api_output(WR_APICODE_SUCCESS);
		}
	}

	new wr_setTheme();