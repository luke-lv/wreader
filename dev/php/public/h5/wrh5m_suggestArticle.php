<?php
	include(dirname(__FILE__).'/__global.php');


	class wrh5m_suggestArticle extends wr_h5mobileController
	{
		private $oBizSuggArt;
		public function main()
		{
			ml_factory::load_standard_conf('wreader_jobs');






			$job = $this->__visitor['job']['job_id'];

			$this->oBizSuggArt = new ml_biz_getSuggestContent($job);
			$this->oBizSuggArt->execute();
			$this->oBizSuggArt->getArticleListByPage(1);
			$data['articleList'] = $this->oBizSuggArt->get_data();

			$this->page_output('suggestArticle' , $data);
		}
	}

	new wrh5m_suggestArticle();