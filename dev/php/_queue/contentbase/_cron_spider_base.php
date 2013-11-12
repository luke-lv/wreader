<?php
include(dirname(dirname(dirname(__FILE__))).'/__global.php');
include(SERVER_ROOT_PATH.'/include/config/ml_spider_config.php');


class _cron_spider_base
{
	private $spidertime;
	private $oArticle;
	private $oSource;
	private $oArticleContent;
	private $oAdminCommon;

	

	public function set_spider_time($spidertime)
	{
		$this->oArticle = new ml_model_wrcArticle();
		$this->oArticleContent = new ml_model_wrcArticleContent();
		$this->oAdminCommon = new ml_model_admin_dbCommon();
		$this->spidertime = $spidertime;
	}
	public function execute()
	{
		$this->oSource = new ml_model_wrcSource();


		$srcPage = 1;
		while (true) {
			$this->oSource->listBySpidertime($this->spidertime , $srcPage);
			$srcRows = $this->oSource->get_data();

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
		echo $srcRow['rss']."\n";
		$rssData = $oLastRss->Get($srcRow['rss']);
		if(!$rssData){
			$rssData = $oLastRss->Get($srcRow['rss']);
			if(!$rssData){
				//出错 报错
				echo 'fetch_rss_error'."\n";
			}
		}

		//对比更新时间与最后抓取时间
		if(!$this->_is_fetched_by_time($srcRow['id'] , $rssData['items'][0]['pubDate']))
		{
			
			foreach ($rssData['items'] as $articleRow) 
			{
				
				$articleRow['link'] = Tool_string::trimCdata($articleRow['link']);
				$articleRow['title'] = Tool_string::trimCdata($articleRow['title']);
				
				//临时去掉过去的
				if(strtotime($articleRow['pubDate'])< strtotime('20130601'))
					continue;

				if(!$this->_is_article_fetched($srcRow['id'] , $articleRow['title'] , $articleRow['link']))
				{


					if($srcRow['spider_type'] == ML_SPIDERTYPE_RSSHTML)
					{
						echo 'fetch_content:'.$articleRow['link']."\n";
						$articleRow['description'] = $this->_fetchByHtml($articleRow['link'] , $srcRow);
					}

					if($srcRow['charset']==ML_CHARSET_GBK)
					{
						$articleRow['title'] = Tool_string::gb2utf($articleRow['title']);
					}

					
					//sleep(3);

					
					
					$aTag = ml_function_lib::segmentChinese($articleRow['title']);
					array_filter($aTag);
        			$aTag = array_merge($aTag , $srcRow['tags']);
					
					$articleRow = $this->_formatBySource($srcRow['codeSign'] , $articleRow);

					$oBizTag2jc = new ml_biz_articleTag2jobContent();
					$articleRow['jobContentId'] = $oBizTag2jc->execute($aTag);
					

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

	private function _fetchByHtml($link , $srcRow)
	{
		$html = Tool_http::get($link);


		if($srcRow['charset'] == ML_CHARSET_GBK)
			$html = Tool_string::gb2utf($html);

		if($html)
		{
			$classname = ml_tool_contentFormater_baseBySrc::calcFormaterClassName($srcRow['codeSign']);
			
			if(class_exists($classname) && method_exists($classname, 'getContentByPage'))
				return $classname::getContentByPage($html);
			else
			 	return ml_tool_rssContent::parseHtml2Content($html);


		}
		return false;
	}
	private function _is_fetched_by_time($srcId , $pub_time)
	{
		return false;

	}

	private function _update_last_fetched_time($srcId)
	{
		$this->oSource->updateLastSpiderTime($srcId);

	}
	
	private function _formatBySource($srcCodesign , $articleRow)
	{
		$classname = ml_tool_contentFormater_baseBySrc::calcFormaterClassName($srcCodesign);
		if (class_exists($classname)) {

			//if(method_exists($classname, 'formatLink'))
			//	$articleRow['link'] = $classname::formatLink($articleRow['link']);

		}
		return $articleRow;
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
			'tags' => $articleRow['tags'],
			'jobContentId' => $articleRow['jobContentId'],
			'pub_time' => date('Y-m-d H:i:s' , strtotime($articleRow['pubDate'])),
		);

		$article_id = $this->oArticle->hash_article_id(date('Ymd' , strtotime($data['pub_time'])) , $srcId , $data['title']);
		
		$this->oArticle->std_addRow($article_id , $srcId , $data);
		

		$data = array(
			'content' => ml_tool_rssContent::rss2article($articleRow['description']),
		);
		$this->oArticleContent->std_addRow($srcId , $article_id , $data);

		if(!empty($articleRow['tags']))
		{
			var_dump($articleRow['tags']);
			var_dump($articleRow['jobContentId']);
			echo 'xxxx';
			ml_tool_queue_contentBase::add_content2redis($article_id , $articleRow['tags'] , $articleRow['jobContentId']);
		}

		return true;
	}

	
}

