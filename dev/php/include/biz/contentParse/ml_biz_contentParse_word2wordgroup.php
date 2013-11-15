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
		$aUnusePreWord = array('所');
		$aUnuseWord = array('的','了' , '要' , '将');
		foreach ($wordInfoRows as $wordInfo) {


			if(in_array($wordInfo['word'], $aMark) //标点断句

				or ($last &&  strpos($wordInfo['word'], $last['word']) === 0)	//重复字
				or in_array($wordInfo['word'], $aUnuseWord)	//没啥用的字符
				or mb_substr($last['word'], -1 , 1 , 'utf-8') == mb_substr($wordInfo['word'], 0, 1 , 'utf-8')//重复字 由于用了二分匹配 可能出现 我爱 爱你
				//or in_array($last, $aUnusePreWord)
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
	public function execute_2($string){
		$aWordInfo = ml_tool_chineseSegment::segmentWithAttr(ml_tool_chineseSegment::filterUnavailableStr($string) , false , true);

		$wordCnt = count($aWordInfo);
		foreach ($aWordInfo as $key => $value) {
			if(!in_array($value['word'], ml_tool_chineseSegment::$_unuseChar)){
				$aWordCnt[$value['word']]++;
				$aWordOffset[$value['word']][] = $value['off'];
				$aWordAttr[$value['word']] = $value['attr'];
			}
		}
		
		arsort($aWordCnt);
		foreach ($aWordCnt as $word => $cnt) {
			$idf = $cnt/$wordCnt;
			if($idf > 0.001){
				
				foreach ($aWordOffset[$word] as $value) {
					$aOff2Word[$value] = $word;
				}
			}
		}

		$aOffset = array_keys($aOff2Word);
		sort($aOffset);

		$wording = '';
		$endOffset = 0;
		$wordLen = 0;
		foreach ($aOffset as  $value) {
			
			if($value!=$endOffset || $aWordAttr[$aOff2Word[$value]] == 'un'){

				if(Tool_string::count_all_character($wording) > 1 && $wordLen > 1){
					$aWordgroup[] = $wording;
				}
				
				$wording = '';
				$wordLen=0;
		
			}

			if($aWordAttr[$aOff2Word[$value]] != 'un'){			
				$endOffset = $value+strlen($aOff2Word[$value]);
				$wording.=$aOff2Word[$value];
				$wordLen++;

				if($wordLen>1){
					$aWordgroup[] = $wording;
				}
			}
		}
		$aWordGroupCnt = array_count_values($aWordgroup);
		arsort($aWordGroupCnt);
		var_dump($aWordGroupCnt);

		

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
