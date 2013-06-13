<?php
include('../../__global.php');
include(SERVER_ROOT_PATH.'/include/config/ml_spider_config.php');
include(SERVER_ROOT_PATH.'/include/ml_function_lib.php');

class _cron_spider_base
{
	private $spidertime;
	private $oArticle;
	private $oArticleContent;
	private $oAdminCommon;

	public function __construct($spidertime)
	{
		$this->oArticle = new ml_model_wrcArticle();
		$this->oArticleContent = new ml_model_wrcArticleContent();
		$this->oAdminCommon = new ml_model_admin_dbCommon();
		$this->spidertime = $spidertime;
	}
	public function execute()
	{
		$oSource = new ml_model_wrcSource();


		$srcPage = 1;
		while (true) {
			$oSource->listBySpidertime($this->spidertime , $srcPage);
			$srcRows = $oSource->get_data();

			if(!$srcRows)
				break;

			foreach ($srcRows as $key => $srcRow) {

				if($srcRow['spider_type'] == ML_SPIDERTYPE_RSS || $srcRow['spider_type'] == ML_SPIDERTYPE_RSSHTML){
					$this->_spider_rss($srcRow);
				}
			}


			$srcPage++;
		}
		

	}	
	private function _spider_rss($srcRow)
	{
		$oLastRss = new Lib_lastRss();
		$rssData = $oLastRss->Get($srcRow['rss']);
		if(!$rssData){
			$rssData = $oLastRss->Get($srcRow['rss']);
			if(!$rssData){
				//出错 报错
			}
		}
		//对比更新时间与最后抓取时间
		if(!$this->_is_fetched_by_time($srcRow['id'] , $rssData['items'][0]['pubDate']))
		{
			foreach ($rssData['items'] as $articleRow) 
			{

				if(!$this->_is_article_fetched($srcRow['id'] , $articleRow['title'] , $articleRow['link']))
				{



					if($srcRow['spider_type'] == ML_SPIDERTYPE_RSSHTML)
						$articleRow['description'] = $this->_fetchByHtml($articleRow['link']);
					
					$seg = ml_function_lib::segmentChinese($articleRow['title']);
					$rs = $this->oAdminCommon->tags_get_by_tag($seg);
					$tags = $this->oAdminCommon->get_data();

					$tags = Tool_array::format_2d_array($tags, 'tag' , Tool_array::FORMAT_VALUE_ONLY );
					

					if(count($tags) > 0)
					{
						$articleRow['tags'] = $tags;

					}

					$this->_write_in_article($srcRow['id'],$articleRow);
					

				}
				else
				{
					//如果文章已经写入基本说明已经抓取，所以后面直接跳出
					break;

				}
			}

		}


		$this->_update_last_fetched_time($srcRow['id']);

		
	}

	private function _fetchByHtml($link)
	{
		$html = Tool_http::get($link);
		

		if($html)
		{
			/**
			 * todo 字符集判断
			 */

			
			 ml_tool_rssContent::parseHtml2Content($html);

		}
		return false;
	}
	private function _is_fetched_by_time($srcId , $pub_time)
	{
		return false;

	}

	private function _update_last_fetched_time($srcId)
	{
		return;

	}

	//判断文章是否已经抓取
	private function _is_article_fetched($srcId , $title , $link)
	{
		$this->oArticle->countByLink($srcId , $link);
		if($this->oArticle->get_data() > 0)
			return true;
		$this->oArticle->countByTitle($srcId , $title);
		if($this->oArticle->get_data() > 0)
			return true;
		else
			return false;
	}

	private function _write_in_article($srcId , $articleRow)
	{
		$data = array(
			'title' => $articleRow['title'],
			'link' => $articleRow['link'],
			'tags' => implode(',', $articleRow['tags']),
			'pub_time' => date('Y-m-d H:i:s' , strtotime($articleRow['pubDate'])),
		);
		$this->oArticle->std_addRow($srcId , $data);
		$article_id = $this->oArticle->insert_id();

		$data = array(
			'content' => ml_tool_rssContent::rss2article($articleRow['description']),
		);
		$this->oArticleContent->std_addRow($srcId , $article_id , $data);
		return true;
	}
}

