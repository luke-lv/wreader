<?php

class ml_tool_admin_view
{
    static public function get_page($total, $numperpage, $curr_page ,$url_query = '', $format = '')
    {
        if($total < $numperpage)
            return '';
        $sHtml = '';
        $total_page = ceil( $total / $numperpage );
        $curr_page = $curr_page > $total_page ? $total_page : $curr_page;
        //
        
        if(empty($url_query))
        {
            parse_str($_SERVER['QUERY_STRING'] , $aQuery);
            unset($aQuery['p']);
            $url = '?'.http_build_query($aQuery);
        }   
        else
            $url = '?'.$url_query;
        
        //�ӵڼ�ҳ��ʼ
        $from = $curr_page - 6;
        $from = $from < 1 ? 1 : $from;
    
        //���ڼ�ҳ����
        $to = $curr_page + 6;
        $to = $to > $total_page ? $total_page : $to;
        
           
    
        //��ҳ
        if($total_page > 1 && $curr_page <> 1)
        {
            $url_page = $format ? str_replace('{page}' , 1 , $format) : $url.'&p=1';
            $sHtml .= "<a href=\"".($url_page)."\">|&lt;</a>&nbsp;&nbsp;"; 
        }
    
        //��ҳ֮ǰ��
        for($i = $from ; $i < $curr_page ; $i++)
        {
            $url_page = $format ? str_replace('{page}' , $i , $format) : $url.'&p='.$i;
           $sHtml .= "<a href=\"" . ($url_page). "\">" . $i ."</a>&nbsp;&nbsp;";
        }
        
        //��ҳ
        $sHtml .= '<font color="#ff0000;">'.$curr_page.'</font>&nbsp;&nbsp;';
        
        //��ҳ֮���
        for($i = $curr_page+1 ; $i <= $to ; $i++)
        {
            $url_page = $format ? str_replace('{page}' , $i , $format) : $url.'&p='.$i;
           $sHtml .= "<a href=\"" . ($url_page) . "\">" . $i ."</a>&nbsp;&nbsp;";
        }
        
        //βҳ
        if($total_page > 1 && $curr_page <> $total_page)
        {
            $url_page = $format ? str_replace('{page}' , $total_page , $format) : $url.'&p='.$total_page;
            $sHtml .= "<a href=\"" . ($url_page). "\">&gt;|</a>";
        }
    
        return $sHtml;
    }
}