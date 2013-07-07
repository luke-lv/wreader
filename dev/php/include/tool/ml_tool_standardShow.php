<?php
class ml_tool_standardShow
{
    /**
     * 时间格式化
     *
     * @param string $ctime yyyy-mm-dd HH:ii:ss
     * @param bool   $is_pin 是否是pin时间格式
     * @return string
     */
    static public function time($ctime,$is_pin=false)
    {
        $result=null;
        $ctime = strtotime($ctime);
        if(date("n月j日", $ctime)==date("n月j日", $_SERVER['REQUEST_TIME'])){
            $time=$_SERVER['REQUEST_TIME'] - $ctime;
            if($time == 0)
            {
                $result='刚刚';
            }
            elseif ($time>0&&$time<=59){
                $result=$time."秒前";
            }
            elseif ($time>=60&&$time<=3599){
                $temp=floor($time/60);
                $result= $temp."分钟前";
            }
            elseif($time>=3600&&$time<=86399){
                $result= "今天".date("H:i",$ctime);
            }
        }else{
            if(!$is_pin)
            $result=date("n月j日 H:i",$ctime);
            else 
            $result=date("n月j日",$ctime);
        }
        return $result;
    }

    static public function rmb_price($price)
    {

        return '￥'.$price;
    }

    static public function no_html($string)
    {
        return strip_tags($string , '<b> <i> <strong> <pre> <img> <br> <a>');
    }


}
