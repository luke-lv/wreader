<?php
class ml_biz_getSuggestContent
{
	private $_job;
	private $_jobConf;
	private $_jobAbility;
	private $oRdsCB;
	private $oDbTag;
	private $oArticle;
	private $oSource;

	private $_aBasicArticle;

	private $_aRsArticle;
	private $_data;


	const SC_KEYPREFIX = 'BSC_';
	const SC_BASICEXPIRE = 600;

	public function __construct($job)
	{
		$this->_job = $job;
		$this->oRdsCB = new ml_model_rdsContentBase();
	}


	private function _fetchBasicTagArticle()
	{
		$rdsKey = self::SC_KEYPREFIX.'jbBscArtUn_'.$this->_jobConf['sign'];


		//$this->_aBasicArticle = $this->oRdsCB->zrange($rdsKey , 0 , -1);

		if(empty($this->_aBasicArticle))
		{
			$aTag = $this->_jobAbility['basicAbilityTag'];
			$aHash = $this->_tag2hash($aTag);


			$this->oRdsCB->unionByTaghashes($rdsKey , $aHash);
			$this->_aBasicArticle = $this->oRdsCB->zRevRange($rdsKey , 0 , -1);
		

			$this->oRdsCB->setTimeOut($rdsKey , self::SC_BASICEXPIRE);
			return true;
		}
		else
		{
			return true;
		}
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

		foreach ($jobsConf as $key => $value) {
			if(isset($value['jobs'][$this->_job]))
				$this->_jobConf = $value['jobs'][$this->_job];
		}

		if(!$this->_jobConf)
			return false;

		$this->_jobAbility = ml_factory::load_standard_conf('wreader_job'.ucfirst($this->_jobConf['sign']));
		if(!$this->_jobAbility)
		{
			return false;
		}

		$this->_fetchBasicTagArticle();
		$this->_aRsArticle = $this->_aBasicArticle;


		return true;		
	}
	public function getArticleListByPage($page , $pagesize = 10)
	{
		$start = ($page-1)*$pagesize;
		$aAids = array_slice($this->_aRsArticle, $start , $pagesize);
		$rs = $this->_fetchArticleInfo($aAids);
		$aArticle = $this->get_data();
		if(!$rs)
			return false;
		$aSrcId = array_unique(Tool_array::format_2d_array($aArticle , 'source_id' , Tool_array::FORMAT_VALUE_ONLY));
		$this->_fetchSourceInfo($aSrcId);
		$aSid2site = $this->get_data();

		foreach ($aArticle as &$value) {
			$value['site_info'] = $aSid2site[$value['source_id']];
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