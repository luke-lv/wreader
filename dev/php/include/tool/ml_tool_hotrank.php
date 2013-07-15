<?php
class ml_tool_hotrank
{
    const TIME_BLOCK = 45000;
    const STARTTIME = 1314835200;
    const LIKE_PERCENT = 0.4;
    const CMT_PERCENT = 0.2;
    const PV_PERCENT = 0.4;
    const SCORE_PERCENT = 0.6;
    
    const SEARCH_PV_PERCENT = 0.6;
    const SEARCH_ATTI_PERCENT = 0.4;
    const SEARCH_MULTIPLE = 0.1;
    
    const SEARCH_USERPOP_ATTI_PERCENT = 0.5;
    const SEARCH_USERPOP_FAN_PERCENT = 0.3;
    const SEARCH_USERPOP_MEILA_PERCENT = 0.2;
    
    public function calc_hotrank($ctime , $like_cnt=0 , $cmt_cnt=0 , $pv=0 , $machine_rank = 0)
    {
        $pt = $like_cnt*self::LIKE_PERCENT + $cmt_cnt*self::CMT_PERCENT  + $pv*self::PV_PERCENT;
        if($pt<1)
            return 0;
        return log10( $pt ) + ($ctime - self::STARTTIME )/self::TIME_BLOCK ;
    }
    
    static public function calc_search_hotrank($atti_sum=0 , $pv=0)
    {
        $pt = $atti_sum*self::SEARCH_ATTI_PERCENT  + $pv*self::SEARCH_PV_PERCENT;
        
        return intval($pt* self::SEARCH_MULTIPLE);
    }
    
    static public function calc_search_user_popular($atti=0 , $fan=0, $meila=0)
    {
        $pt = $atti*self::SEARCH_USERPOP_ATTI_PERCENT  + $fan*self::SEARCH_USERPOP_FAN_PERCENT + $meila*self::SEARCH_USERPOP_MEILA_PERCENT;
        
        return intval($pt* self::SEARCH_MULTIPLE);
    }

    public static function getguanghotrank($ctime, $score, $like_cnt = 0, $cmt_cnt = 0, $pv = 0) {
        $pt = $score * 100 * self::SCORE_PERCENT + $like_cnt * self::LIKE_PERCENT + $cmt_cnt * self::CMT_PERCENT + $pv * self::PV_PERCENT;
        if ($pt < 1)
            return 0;
        return log10 ( $pt ) + ($ctime - self::STARTTIME) / self::TIME_BLOCK;
    }
}

