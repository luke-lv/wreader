<?php
include(dirname(dirname(dirname(__FILE__))).'/__global.php');
class parse_lagou_job
{
	function execute($filename){
		$fp = fopen('./jobs/'.$filename, 'r');

		$aJobKeyword = array();
		$aAttrPass = array('c' , 'uj' , 'r' , 'd' , 'm' , 'p' , 'f' , 'un' , 'q' , 'mt');

		while ($line = trim(fgets($fp))) {
			list($jobName , $jobDesc , $jobContent) = explode('----', $line);

			$job_level = $this->_calc_job_level($jobName);

			$content.=$jobContent;
		}
		$oBiz = new ml_biz_contentParse_word2wordgroup;
		$a = $oBiz->execute($content);
		
			var_dump($a);
	}

	function _calc_job_level($jobName){
		return strpos($jobName, '高级')?2:0;
	}
}

$o = new parse_lagou_job;
$o->execute('job_php.log');