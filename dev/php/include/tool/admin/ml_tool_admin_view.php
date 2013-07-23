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
        
        //从第几页开始
        $from = $curr_page - 6;
        $from = $from < 1 ? 1 : $from;
    
        //到第几页结束
        $to = $curr_page + 6;
        $to = $to > $total_page ? $total_page : $to;
        
           
    
        //首页
        if($total_page > 1 && $curr_page <> 1)
        {
            $url_page = $format ? str_replace('{page}' , 1 , $format) : $url.'&p=1';
            $sHtml .= "<a href=\"".($url_page)."\">|&lt;</a>&nbsp;&nbsp;"; 
        }
    
        //本页之前的
        for($i = $from ; $i < $curr_page ; $i++)
        {
            $url_page = $format ? str_replace('{page}' , $i , $format) : $url.'&p='.$i;
           $sHtml .= "<a href=\"" . ($url_page). "\">" . $i ."</a>&nbsp;&nbsp;";
        }
        
        //本页
        $sHtml .= '<font color="#ff0000;">'.$curr_page.'</font>&nbsp;&nbsp;';
        
        //本页之后的
        for($i = $curr_page+1 ; $i <= $to ; $i++)
        {
            $url_page = $format ? str_replace('{page}' , $i , $format) : $url.'&p='.$i;
           $sHtml .= "<a href=\"" . ($url_page) . "\">" . $i ."</a>&nbsp;&nbsp;";
        }
        
        //尾页
        if($total_page > 1 && $curr_page <> $total_page)
        {
            $url_page = $format ? str_replace('{page}' , $total_page , $format) : $url.'&p='.$total_page;
            $sHtml .= "<a href=\"" . ($url_page). "\">&gt;|</a>";
        }
    
        return $sHtml;
    }

    static public function echoline($dataDefine , $field , $value)
    {
        $df = ml_factory::load_dataDefine($dataDefine);

        if(in_array($df['field'][$field]['type'], array('i','s')))
        {
            echo htmlspecialchars($value);
        }
        else if($df['field'][$field]['type']=='enum')
        {
            
            echo $df['field'][$field]['enum'][$value];
        }
        else
        {
            echo htmlspecialchars($value);
        }
    }

    static public function dtdfn_input($type ,$name, $dtdfn , $value = null , $id = null)
    {
        if(in_array($type, array('i','s')))
        {
            return '<input type="text" name="'.$name.'" value="'.(is_null($value)?$dtdfn['default']:$value).'"/>';
        }
        else if($type=='enum')
        {
            $options = '';
            foreach ($dtdfn['enum'] as $key => $cn) {
                $options.='<option value="'.$key.'"'.($value==$key?' selected':'').'>'.$cn.'</option>';
            }

            $id_str = !is_null($id) ? ' id="'.$id.'"' : '';
            return '<select name="'.$name.'"'.$id_str.'>'.$options.'</select>';
        }

    }


}