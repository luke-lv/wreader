<?php

class ml_tool_admin_view
{
    const ECHO_EXTRA_LEN = 'len';
    static public function get_page($total, $numperpage, $curr_page ,$url_query = '', $format = '')
    {
        if($total < $numperpage)
            return '';
        $sHtml = '';
        $total_page = ceil( $total / $numperpage );
        $curr_page = $_GET['p'] ? $_GET['p']:1;
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
        
        //´ÓµÚ¼¸Ò³¿ªÊ¼
        $from = $curr_page - 6;
        $from = $from < 1 ? 1 : $from;
    
        //µ½µÚ¼¸Ò³½áÊø
        $to = $curr_page + 6;
        $to = $to > $total_page ? $total_page : $to;
        
           
    
        //Ê×Ò³
        if($total_page > 1 && $curr_page <> 1)
        {
            $url_page = $format ? str_replace('{page}' , 1 , $format) : $url.'&p=1';
            $sHtml .= "<a href=\"".($url_page)."\">|&lt;</a>&nbsp;&nbsp;"; 
        }
    
        //±¾Ò³Ö®Ç°µÄ
        for($i = $from ; $i < $curr_page ; $i++)
        {
            $url_page = $format ? str_replace('{page}' , $i , $format) : $url.'&p='.$i;
           $sHtml .= "<a href=\"" . ($url_page). "\">" . $i ."</a>&nbsp;&nbsp;";
        }
        
        //±¾Ò³
        $sHtml .= '<font color="#ff0000;">'.$curr_page.'</font>&nbsp;&nbsp;';
        
        //±¾Ò³Ö®ºóµÄ
        for($i = $curr_page+1 ; $i <= $to ; $i++)
        {
            $url_page = $format ? str_replace('{page}' , $i , $format) : $url.'&p='.$i;
           $sHtml .= "<a href=\"" . ($url_page) . "\">" . $i ."</a>&nbsp;&nbsp;";
        }
        
        //Î²Ò³
        if($total_page > 1 && $curr_page <> $total_page)
        {
            $url_page = $format ? str_replace('{page}' , $total_page , $format) : $url.'&p='.$total_page;
            $sHtml .= "<a href=\"" . ($url_page). "\">&gt;|</a>";
        }
    
        return $sHtml;
    }

    static public function echoline($dataDefine , $field , $value , $extra = array())
    {
        $df = ml_factory::load_dataDefine($dataDefine);

        if(in_array($df['field'][$field]['type'], array('i','s')))
        {
            if($extra[self::ECHO_EXTRA_LEN] > 0)
                $value = Tool_string::substr_by_width($value , 0 ,$extra[self::ECHO_EXTRA_LEN]);
            echo htmlspecialchars($value);
        }
        else if($df['field'][$field]['type']=='enum')
        {
            
            echo $df['field'][$field]['enum'][$value];
        }
        else if($df['field'][$field]['type']=='a')
        {
            
            echo htmlspecialchars(implode(' ', $value));
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
        else if($type== 'a')
        {
            return '<input type="text" name="'.$name.'" value="'.(is_null($value)?$dtdfn['default']:implode(' ', $value)).'"/>';
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

    static public function html_select($name , $aValue2str , $value = '' , $id = '' , $class = '' , $isUnshiftNone = false)
    {
        if ($isUnshiftNone) {
            $options.='<option value="0">无</option>';
        }
        foreach ($aValue2str as $key => $cn) {
                $options.='<option value="'.$key.'"'.($value==$key?' selected':'').'>'.$cn.'</option>';
            }

            $id_str = !empty($id) ? ' id="'.$id.'"' : '';
            $class_str = !empty($class) ? ' class="'.$class.'"' : '';
            return '<select name="'.$name.'"'.$id_str.$class_str.'>'.$options.'</select>';
    }
}