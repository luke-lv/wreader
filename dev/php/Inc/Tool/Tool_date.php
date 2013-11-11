<?php

class Tool_date
{
	
	
	function offsetMonth($offset){
		$monthes = (date('Y')*12 + date('m'))+$offset;
		if($monthes%12==0){
			return (floor($monthes/12)-1).'12';
		}else{
			return floor($monthes/12).str_pad($monthes%12 , 2 , 0 , STR_PAD_LEFT);
		}
	}
}


