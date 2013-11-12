<?php
class ml_biz_getSuggestContent
{
	private $_uid;
	private $_job;
	private $_userJob;
	private $_jobConf;
	private $_jobAbility;
	private $oRdsCB;
	private $oDbTag;
	private $oArticle;
	private $oSource;

	private $_aBasicArticle;
	private $_aAttendTagArticle;

	private $_aRsArticle;
	private $_data;

	const SC_SUGTYPE_TREE = 'tree';
	const SC_SUGTYPE_ATTEND = 'attend';
	const SC_SUGTYPE_SELF = 'self';

	const SC_KEYPREFIX = 'BSC_';
	const SC_BASICEXPIRE = 600;

	const SC_READMORE_WEIGHT = 1.05;
	const SC_ATTEND_WEIGHT = 1.2;

	public function __construct($uid , $userJob)
	{
		$this->_uid = $uid;
		$this->_userJob = $userJob;
		$this->_job = $this->_userJob['job_id'];
		$this->oRdsCB = new ml_model_rdsContentBase();
	}


	private function _fetchBasicTagArticle()
	{
		global $ML_RECOMMENDLEVEL_WEIGHT;
		if(empty($this->_aBasicArticle))
		{
			$oJob2jc = new ml_model_wrcJob2jobContent();
			$oJob2jc->get_by_jobid($this->_job);
			$row = $oJob2jc->get_data();
			$aJobContentId = array_keys($row['jobContentIds']);
			foreach ($aJobContentId as $jcid) {
				$aWeight[] = $ML_RECOMMENDLEVEL_WEIGHT[$row['jobContentIds'][$jcid]['rcmdLv']];
			}

			$this->oRdsCB->unionByJobContentId($this->_job , $aJobContentId , $aWeight);
			$this->_aBasicArticle = $this->oRdsCB->listArticleByJobId($this->_job);
		

			//$this->oRdsCB->setTimeOut($rdsKey , self::SC_BASICEXPIRE);
			return true;
		}
		else
		{
			return true;
		}
	}
	private function _fetchattendTagArticle()
	{
		$rdsKey = self::SC_KEYPREFIX.'uAttTgArtUn_'.$this->_uid;
		$rdsBasicKey = self::SC_KEYPREFIX.'jbBscArtUn_'.$this->_jobConf['sign'];


		$oRdsReaded = new ml_model_rdsUserReaded();
		$aReadedTag = $oRdsReaded->getReadedTag($this->_uid , true);
		
		$aReadedTagHash = array_keys($aReadedTag);

		$aAttendTagHash = $this->_tag2hash($this->_userJob['attend_tag']);

		$aAllTaghash = array_unique( array_values( array_merge($aReadedTagHash , array_values($aAttendTagHash))));
		
		$aAllHash2Weight = array_combine(
								$aAllTaghash, array_pad(array()
								, count($aAllTaghash), 1));

		foreach ($aAllHash2Weight as $hash => &$value) {

			if(in_array($hash, $aAttendTagHash))
				$value = self::SC_ATTEND_WEIGHT;
			else if(in_array($hash, $aReadedTagHash)){

				$value = self::SC_READMORE_WEIGHT + ((int)$aReadedTag[$hash] /5);
			}
			
		}
		

		$aHash = $this->_tag2hash($this->_userJob['attend_tag']);
		$this->oRdsCB->unionByTaghashes($rdsKey , $aHash , array($rdsBasicKey) , $aAllHash2Weight);
		$this->_aAttendTagArticle = $this->oRdsCB->zRevRange($rdsKey , 0 , -1);

	

		$this->oRdsCB->delete($rdsKey);
		return true;
	}
	private function _tag2hash($aTag)
	{
		if(!empty($aTag))
		{
			foreach ($aTag as $key => $value) {
				$aTag2hash[$value] = ml_model_admin_dbTag::tag_hash($value);
			}
		}
		return $aTag2hash;
	}

	public function execute()
	{
		$jobsConf = ml_factory::load_standard_conf('wreader_jobs');

		//取职业配置
		foreach ($jobsConf as $key => $value) {
			if(isset($value['jobs'][$this->_job]))
				$this->_jobConf = $value['jobs'][$this->_job];
		}
		if(!$this->_jobConf)
			return false;

		//取职业能力
		$this->_jobAbility = ml_factory::load_standard_conf('wreader_job'.ucfirst($this->_jobConf['sign']));
		if(!$this->_jobAbility)
		{
			return false;
		}

		//根据职业取基本文章
		$this->_fetchBasicTagArticle();
		//$this->_fetchattendTagArticle();

		//$this->_aRsArticle = $this->_aAttendTagArticle + $this->_aBasicArticle;

		$this->_aRsArticle = $this->_aBasicArticle;
		


		return true;		
	}
	public function getArticleListByPage($page , $pagesize = 500)
	{
		$start = ($page-1)*$pagesize;
		$aAids = array_slice($this->_aRsArticle, $start , $pagesize);
		$rs = $this->_fetchArticleInfo($aAids);
		$aArticle = $this->get_data();

		$oRdsReaded = new ml_model_rdsUserReaded();
		$aReaded = $oRdsReaded->getReadedByArticleId($this->_uid , $aAids);


		if(!$rs)
			return false;
		$aSrcId = array_unique(Tool_array::format_2d_array($aArticle , 'source_id' , Tool_array::FORMAT_VALUE_ONLY));
		$this->_fetchSourceInfo($aSrcId);
		$aSid2site = $this->get_data();

		foreach ($aArticle as &$value) {
			$value['site_info'] = $aSid2site[$value['source_id']];
			if(in_array($value['id'], $this->_aBasicArticle))
				$value['suggestType'] = self::SC_SUGTYPE_TREE;
			else if(in_array($value['id'], $this->_aAttendTagArticle))
				$value['suggestType'] = self::SC_SUGTYPE_ATTEND;

			$value['readed'] = $aReaded[$value['id']]==1 ? true : false;
		}

		$this->_data = $aArticle;
		return true;
	}
	public function get_data()
	{
		return $this->_data;
	}


	private function _fetchArticleInfo($aAids)
	{
		if(!is_object($this->oArticle))
			$this->oArticle = new ml_model_wrcArticle();

		$this->oArticle->getArticleByIds($aAids);
		$this->_data = $this->oArticle->get_data();
		
		return true;
	}
	private function _fetchSourceInfo($aSrcId)
	{
		if(!is_object($this->oSource))
			$this->oSource = new ml_model_wrcSource();

		$this->oSource->getRowsByIds($aSrcId);
		$aSid2site = Tool_array::format_2d_array($this->oSource->get_data() , 'id' , Tool_array::FORMAT_FIELD2ROW);

		$this->_data = $aSid2site;
		return true;
	}
}