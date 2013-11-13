<?php
class ml_biz_getSuggestContent
{
	private $_uid;
	private $_job_id;
	private $_userJob;
	private $oRdsCB;
	private $oArticle;
	private $oSource;
	private $_readedTagHash2cnt;
	private $_aBasicArticle;
	private $_aAttendTagArticle;

	private $_aRsArticle;
	private $_data;

	const SC_SUGTYPE_TREE = 'tree';
	const SC_SUGTYPE_ATTEND = 'attend';
	const SC_SUGTYPE_READED = 'readed';

	const SC_BASICEXPIRE = 600;


	public function __construct($uid , $userJob)
	{
		$this->_uid = $uid;
		$this->_userJob = $userJob;
		$this->_job_id = $this->_userJob['job_id'];
		$this->oRdsCB = new ml_model_rdsContentBase();
	}

	public function execute($page , $pagesize = 50)
	{
		$oRdsReaded = new ml_model_rdsUserReaded;
		$this->_readedTagHash2cnt = $oRdsReaded->getReadedTag($this->_uid , true);
		

		//取针对用户的推荐列表
		$articleIds = $this->_fetchMySuggestedArticleidsByPage($page , $pagesize);
		//没有则创建用户的推荐列表
		if(empty($articleIds)){
			//取职业公用文章列表
			$this->_fetchJobSuggestedArticleids();

			//取我要看的文章列表
			$this->_fetchMyAttentionArticleids();
			
			//取我读过的标签的文章列表
			$this->_fetchMyReadedArticleids();
			

			//缓存我的文章列表
			$rs = $this->oRdsCB->unionForUserAll($this->_uid , $this->_job_id);
			$articleIds = $this->_fetchMySuggestedArticleidsByPage($page , $pagesize);
		}
		
		$this->_aRsArticle = $articleIds;

		return true;
	}
	//取已生成好的推荐列表
	private function _fetchMySuggestedArticleidsByPage($page , $pagesize){
		return $this->oRdsCB->fetchUserAllSuggested($this->_uid , $page , $pagesize);

	}
	//取职业推荐文章列表
	private function _fetchJobSuggestedArticleids(){
		global $ML_RECOMMENDLEVEL_WEIGHT;

		$this->_aBasicArticle = $this->oRdsCB->listArticleByJobId($this->_job_id);
		if(empty($this->_aBasicArticle))
		{
			$oJob2jc = new ml_model_wrcJob2jobContent();
			$oJob2jc->get_by_jobid($this->_job_id);
			$row = $oJob2jc->get_data();
			$aJobContentId = array_keys($row['jobContentIds']);
			foreach ($aJobContentId as $jcid) {
				$aWeight[] = $ML_RECOMMENDLEVEL_WEIGHT[$row['jobContentIds'][$jcid]['rcmdLv']];
			}

			$this->oRdsCB->unionByJobContentId($this->_job_id , $aJobContentId , $aWeight);
			$this->_aBasicArticle = $this->oRdsCB->listArticleByJobId($this->_job_id);
		

			//$this->oRdsCB->setTimeOut($rdsKey , self::SC_BASICEXPIRE);
			return true;
		}
		else
		{
			return true;
		}
	}
	//取关注标签的文章列表
	private function _fetchMyAttentionArticleids(){
		$attend_tag = $this->_userJob['attend_tag'];
		
		$aAll = array();
		foreach ($attend_tag as $tag) {
			$aTaghash[] = ml_model_admin_dbTag::tag_hash($tag);
			$aWeight[] = 1;	
		}
		
		return $this->oRdsCB->unionForUserAtten($this->_uid , $aTaghash , $aWeight);
	}
	//取常读标签的文章列表
	private function _fetchMyReadedArticleids(){
		
		$aTag = array_slice(array_keys($this->_readedTagHash2cnt), 0 , 3);
		$aTagWeight = array_slice(array_values($this->_readedTagHash2cnt), 0 , 3);
		return $this->oRdsCB->unionForUserReaded($this->_uid , $aTag , $aTagWeight);
	}


	public function getArticleListByPage($page , $pagesize = 500)
	{
		//
		$this->execute($page , $pagesize);
		$aAids = $this->_aRsArticle;

		//取文章
		$rs = $this->_fetchArticleInfo($aAids);
		if(!$rs)
			return false;
		$aArticle = $this->get_data();

		//已读记录
		$oRdsReaded = new ml_model_rdsUserReaded();
		$aReaded = $oRdsReaded->getReadedByArticleId($this->_uid , $aAids);
		//已读标签
		$oTag = new ml_model_admin_dbTag;
		$oTag->tags_get_by_taghash(array_keys($this->_readedTagHash2cnt));
		$aReadedTag = Tool_array::format_2d_array($oTag->get_data() , 'tag' , Tool_array::FORMAT_VALUE_ONLY);

		//取源信息
		$aSrcId = array_unique(Tool_array::format_2d_array($aArticle , 'source_id' , Tool_array::FORMAT_VALUE_ONLY));
		$this->_fetchSourceInfo($aSrcId);
		$aSid2site = $this->get_data();

		foreach ($aArticle as &$value) {
			$value['site_info'] = $aSid2site[$value['source_id']];
			
			
			if(array_intersect($this->_userJob['attend_tag'], $value['tags']) != array())
				$value['suggestType'] = self::SC_SUGTYPE_ATTEND;
			else if(array_intersect($aReadedTag, $value['tags']) != array())
				$value['suggestType'] = self::SC_SUGTYPE_READED;
			else{
				$value['suggestType'] = self::SC_SUGTYPE_TREE;
			}

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