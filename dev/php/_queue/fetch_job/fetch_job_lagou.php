<?php
include('../../include/lib/phpQuery.php');

class fetch_job_lagou
{
	private $job;
	function execute($url , $job){
		$this->job = $job;
		$index = $this->_fetch_html($url);
		preg_match('/pageCount: ([0-9]+)/', $index , $b);
		$max_page = $b[1];


		phpQuery::newDocumentHtml($index);
		$a = pq('.hot_pos')->find('li');
		foreach ($a as $hot_pos) {
			$url_tmp = pq($hot_pos)->find('.mb10 > a:first')->attr('href');
			if(!empty($url_tmp)){
				$aShowUrl[] = $url_tmp;
			}
		}



		for ($i=2; $i <= $max_page; $i++) { 
			
			$listpage_html = $this->_fetch_html($url.'?pn='.$i);
			$a = pq('.hot_pos')->find('li');
			foreach ($a as $hot_pos) {
				$url_tmp = pq($hot_pos)->find('.mb10 > a:first')->attr('href');
				if(!empty($url_tmp)){
					$aShowUrl[] = $url_tmp;
				}
			}
		}
 
		echo 'start fetch show'."\n";

		foreach ($aShowUrl as $showurl) {
		
			$this->_fetch_show($showurl);
			usleep(10000);
		}
	}

	function _fetch_html($url){
		return file_get_contents($url);
	}
	
	function _fetch_show($url){
		$html = $this->_fetch_html($url);
		phpQuery::newDocumentHTML($html);
		$title = pq('title')->text();
		$jobName = substr($title , 0 , strpos($title, '-'));

		$jobDesc = trim(pq('.job_request')->text());
		$aDesc = explode(' / ' , trim(substr($jobDesc, 0 , strpos($jobDesc, "\n"))));

		$jobBt = trim(pq('.job_bt')->text());
		$jobBt = str_replace("\r\n", "", $jobBt);
		$jobBt = str_replace("\n", "", $jobBt);
		
		$this->_savejob($jobName , $aDesc , $jobBt);
	}

	function _savejob($job_name , $aDesc , $jobBt){
		$fp = fopen('./jobs/job_'.$this->job.'.log', 'a');
		fwrite($fp, $job_name.'----'.implode('|', $aDesc).'----'.$jobBt."\n");
		fclose($fp);
	}
}


$o = new fetch_job_lagou;
$o->execute('http://www.lagou.com/jobs/list_运营' , 'op');
