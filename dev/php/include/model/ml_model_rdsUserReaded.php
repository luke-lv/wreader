<?php
class ml_model_rdsUserReaded extends ml_model_redis  
{    
    
    const READEDAID_PREFIX = 'rur_';
    const READEDTAG_PREFIX = 'rut_';
    function __construct() {
        if(!$this->init_rds('meila_contentBase'))
            return false;
    }
    

    public function addReadedArticleId($uid , $article_id)
    {
        $key = self::READEDAID_PREFIX.$uid;
        return $this->hSet($key , $article_id , 1);
    }

    public function getReadedByArticleId($uid , $aArticleId)
    {
        $key = self::READEDAID_PREFIX.$uid;
        
        return $this->hMGet($key ,$aArticleId);
    }
    
    public function setReadedTag($uid , $aTags)
    {
        if(empty($aTags))
            return false;

        $key = self::READEDTAG_PREFIX.$uid;
        foreach ($aTags as $tag) {
            $tag_hash = ml_model_admin_dbTag::tag_hash($tag);
            if(!$this->zIncrBy($key , 1 , $tag_hash))
                return false;
        }
        return true;
    }
    public function getReadedTag($uid , $withScores = false)
    {
        $key = self::READEDTAG_PREFIX.$uid;
        
        return $this->zRevRange($key , 0 , 10 , $withScores);
    }
}