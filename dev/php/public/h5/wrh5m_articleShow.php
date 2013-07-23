<?php
	include(dirname(__FILE__).'/__global.php');


	class wrh5m_suggestArticle extends wr_h5mobileController
	{
		private $oBizSuggArt;
		public function main()
		{
			ml_factory::load_standard_conf('wreader_jobs');
			$job = ML_WR_JOB_TECH_PHP;

			$aid = $this->input('aid');

			$oArticle = new ml_model_wrcArticle();
			$oArticle->std_getRowById($aid);
			$articleInfo = $oArticle->get_data();

			$oArticleC = new ml_model_wrcArticleContent();
			$oArticleC->std_getRowById($aid);
			$articleInfo = array_merge($articleInfo , $oArticleC->get_data());

			$data['articleInfo'] = $articleInfo;

			$oSource = new ml_model_wrcSource();
			$oSource->std_getRowById($articleInfo['source_id']);
			$data['sourceInfo'] = $oSource->get_data();

			$oReaded = new ml_model_wruReadedArticle();
			$oReaded->std_getRowById($this->__visitor['uid'] , $aid);
			$aReaded = $oReaded->get_data();

			$this->set_scope_var('articleReaded' , empty($aReaded) ? 0 : 1);


			$contentLength = Tool_string::count_all_character($articleInfo['content']);
			$minReadTime = 0;//round($contentLength/(1300/60));
			$maxReadTime = round($contentLength/(300/60) * 2);

			$oRdsReaded = new ml_model_rdsUserReaded();
			$oRdsReaded->addReadedArticleId($this->__visitor['uid'] , $aid);

			$this->set_scope_var('minReadTime' , $minReadTime);
			$this->set_scope_var('maxReadTime' , $maxReadTime);
			$this->set_scope_var('articlePageTime' , time());
			$this->set_scope_var('aid' , $aid);
			$this->page_output('articleShow' , $data);
		}
	}

	new wrh5m_suggestArticle();