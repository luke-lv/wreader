<?php
class ml_model_rdsContentBase extends ml_model_redis  
{    
    const KEY_PREFIX = 'rcb_';
    const KEY_PREFIX_JC = 'rcbjc_';
    const KEY_PREFIX_JOB = 'rcbj_';
    const KEY_PREFIX_USERATTEN = 'rcbUa_';
    const KEY_PREFIX_USERREADED = 'rcbUr_';
    const KEY_PREFIX_USERALL = 'rcbUal_';
    function __construct() {
        if(!$this->init_rds('meila_contentBase'))
            return false;
    }
    

    public function addArticleToTag($tagHash , $article_id , $weight = 0)
    {
        $key = self::KEY_PREFIX.$tagHash;
        return $this->zAdd($key , $weight , $article_id);
    }
    public function addArticleToJobContent($jobContentId , $article_id , $weight){
        $key = self::KEY_PREFIX_JC.$jobContentId;
        return $this->zAdd($key , $weight , $article_id);   
    }

    public function listAllTags()
    {
        $aKeys = $this->keys(self::KEY_PREFIX.'*');
        if(!empty($aKeys))
        {
            foreach ($aKeys as $key) {
                $aRs[substr($key, 4)] = $this->zSize($key);
            }
        }
        return $aRs;
    }
    public function unionByTaghashes($destKey , $aTagHash , $aUnionKey = array() , $aTagHash2Weight = array())
    {
        if(!empty($aTagHash))
        {
            foreach ($aTagHash as $key => $value) {
                $aKeys[] = self::KEY_PREFIX.$value;
            }
        }
        
        if(!empty($aUnionKey))
        {
            $aKeys = array_merge($aKeys , $aUnionKey);
        }

        $aWeight = array_pad(array(), count($aKeys), 1);
        $prefix_len = strlen(self::KEY_PREFIX);
        
        foreach ($aKeys as $key => $value) {
            $tagHash = substr($value , $prefix_len);
        
            $aWeight[$key] = $aTagHash2Weight[$tagHash] ? $aTagHash2Weight[$tagHash] : 1 ;
        }
        
        return $this->zUnion($destKey , $aKeys , $aWeight);
    }
    public function unionByJobContentId($job_id , $aJobContentId , $aWeight)
    {
        foreach ($aJobContentId as $jcid) {
            $aKeys[]= self::KEY_PREFIX_JC.$jcid;
        }
        $destKey = self::KEY_PREFIX_JOB.$job_id;

        return $this->zUnion($destKey , $aKeys , $aWeight);
    }
    public function unionForUserAtten($uid , $aTagHash , $aWeight)
    {

        foreach ($aTagHash as $tagHash) {
            $aKeys[]= self::KEY_PREFIX.$tagHash;
        }
        $destKey = self::KEY_PREFIX_USERATTEN.$uid;

        return $this->zUnion($destKey , $aKeys , $aWeight);
    }
    public function unionForUserReaded($uid , $aTagHash , $aWeight)
    {
        foreach ($aTagHash as $key => $tagHash) {
            $aKeys[]= self::KEY_PREFIX.$tagHash;
            $aWeight[$key] = (int)$aWeight[$key];
        }
        $destKey = self::KEY_PREFIX_USERREADED.$uid;
        return $this->zUnion($destKey , $aKeys , $aWeight);
    }
    public function unionForUserAll($uid , $job_id)
    {
        $destKey = self::KEY_PREFIX_USERALL.$uid;
        $aKeys = array(
            self::KEY_PREFIX_JOB.$job_id,
            self::KEY_PREFIX_USERATTEN.$uid,
            self::KEY_PREFIX_USERREADED.$uid,
        );
        $aWeight = array(
            1,
            1.1,
            1.2,
        );
        return $this->zUnion($destKey , $aKeys , $aWeight);
    }
    public function fetchUserAllSuggested($uid , $page = 1 , $pagesize = 50){
        $destKey = self::KEY_PREFIX_USERALL.$uid;
        $start = ($page-1)*$pagesize;
        $end = $page*$pagesize;

        
        return $this->zRevRange($destKey , $start , $end);
    }
    
    public function listArticleByJobId($job_id)
    {
        $key = self::KEY_PREFIX_JOB.$job_id;
        return $this->zRevRange($key , 0 , -1);
    }
    public function listAllJc()
    {
        $aKeys = $this->keys(self::KEY_PREFIX_JC.'*');
        foreach ($aKeys as $key) {
            $n = $this->zSize($key);
            $jobContentId = substr($key, strlen(self::KEY_PREFIX_JC));
            $aRs[$jobContentId] = $n;
        }
        return $aRs;
    }
    public function getByTagHash($hash , $withScores = true){
        $key = self::KEY_PREFIX.$hash;
        return $this->zRange($key , 0 , 100 , $withScores);
    }
}