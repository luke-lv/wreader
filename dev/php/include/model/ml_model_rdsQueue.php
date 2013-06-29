<?php
class ml_model_rdsQueue extends ml_model_redis  
{    
    
    function __construct() {
        if(!$this->init_rds('meila_queue'))
            return false;
    }
    

    public function addQueue($key,$aData) {

        return $this->oRedis->rPush($key , serialize($aData));
    }
    
    public function listQueue()
    {
    	$ids = $this->keys('*');
    	foreach ($ids as $value) {
    		$rs[$value] = $this->lLen($value);
    		echo $value.' '.$rs[$value]."<br/>";
    	}

    }
}