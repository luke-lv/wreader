<?php
	include(dirname(dirname(__FILE__)).'/__global.php');

	class wr_articleReadOver extends wr_h5mobileAPIController
	{

		private $_article_id;

		public function checkParam(){
			$this->_article_id = $this->input('aid');
		}

		public function main()
		{
			
			$oArticle = new ml_model_wrcArticle();
			$oArticle->std_getRowById($this->_article_id);
			$aArticle = $oArticle->get_data();



			$oReaded = new ml_model_wruReadedArticle();
			$oReaded->std_addRow($this->__visitor['uid'] , $this->_article_id , $aArticle['title']);

			$oRdsReaded = new ml_model_rdsUserReaded();
			$rs = $oRdsReaded->setReadedTag($this->__visitor['uid'] , $aArticle['tags']);


			$this->api_output(WR_APICODE_SUCCESS);
		}
	}

	new wr_articleReadOver();