<?php
class ml_model_rdsContentBase extends ml_model_redis  
{    
    const KEY_PREFIX = 'rcb_';
    function __construct() {
        if(!$this->init_rds('meila_contentBase'))
            return false;
    }
    

    public function addArticleToTag($tagHash , $article_id , $weight = 0)
    {
        $key = self::KEY_PREFIX.$tagHash;
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
}