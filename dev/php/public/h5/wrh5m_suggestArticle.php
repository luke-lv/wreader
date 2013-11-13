<?php
	include(dirname(__FILE__).'/__global.php');


	class wrh5m_suggestArticle extends wr_h5mobileController
	{
		private $oBizSuggArt;
		public function main()
		{
			ml_factory::load_standard_conf('wreader_jobs');


			$this->oBizSuggArt = new ml_biz_getSuggestContent($this->__visitor['uid'] , $this->__visitor['userJob']);
			$this->oBizSuggArt->getArticleListByPage(1 , 50);
			$data['articleList'] = $this->oBizSuggArt->get_data();

			$this->page_output('suggestArticle' , $data);
		}
	}

	new wrh5m_suggestArticle();