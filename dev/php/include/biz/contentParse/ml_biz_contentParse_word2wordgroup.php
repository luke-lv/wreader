<?php

class ml_biz_contentParse_word2wordgroup
{
	public function execute($content , $filter = true){
		$content = strip_tags($content);
		$content = str_replace('&nbsp;', '', $content);
		$wordInfoRows = ml_tool_chineseSegment::segmentWithAttr($content);
		$last_idf = 0;
		$group = array();
		$aMark = array(',','.','?','!',':',';','"','\'','，','。', '？', '！', '：', '；', '“', '‘' , '、','”' , '《' , '》');
		$aAttrPass = array('c' , 'uj' , 'r' , 'd' , 'm' , 'p' , 'f' , 'un' , 'q' , 'mt' , 'sn');
		$aUnusePreWord = array('所');
		foreach ($wordInfoRows as $wordInfo) {


			if(in_array($wordInfo['word'], $aMark) //标点断句
				or in_array($wordInfo['attr'], $aAttrPass)	//无用词性
				or ($last &&  strpos($wordInfo['word'], $last['word']) === 0)	//重复字
				// or in_array($wordInfo['word'], $aUnuseWord)	//没啥用的字符
				or mb_substr($last['word'], -1 , 1 , 'utf-8') == mb_substr($wordInfo['word'], 0, 1 , 'utf-8')//重复字 由于用了二分匹配 可能出现 我爱 爱你
				//or in_array($last, $aUnusePreWord)
				or $wordInfo['idf'] == 0
				){
				$last = array();
				continue;
			}
			if($last){
				
					$group[] = $last['word'].$wordInfo['word'].' '.$last['attr'].'-'.$last['idf'].' '.$wordInfo['attr'].'-'.$wordInfo['idf'];
			}
			
			$last = $wordInfo;
		}
		foreach ($group as &$value) {
			$value = strtolower($value);
		}
		$rs = array_count_values($group);
		arsort($rs);
		if($filter){
			foreach ($rs as $key => $value) {
				$wordInfo = array();
				list($wordInfo['word'],$wordInfo['idf'][0],$wordInfo['idf'][1]) = explode(' ', $key);
				$wordInfo['repeat'] = $value;
				$aRs[] = $wordInfo;
			}
			return $aRs;
		}else{
			return $rs;
		}
		
	}
	public function execute_in_multi_article($aContents){
		foreach ($aContents as $con) {
			$aSegment = $this->execute($con);

			foreach ($aSegment as $wordInfo) {

				$repeat = 0;
				if($wordInfo['repeat']>1 and $wordInfo['repeat'] < 4){
					$repeat = 1;
				}elseif($wordInfo['repeat'] >= 4){
					$repeat = 2;
				}


				$aKey2repeat[$wordInfo['word']] +=$repeat;
                $aKeys[$wordInfo['word']] = $wordInfo;
                    
            }

            
		}
		arsort($aKey2repeat);
		return array('sort' => $aKey2repeat , 'wordInfo' => $aKeys);
	}
}
