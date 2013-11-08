<?php

class ml_biz_contentParse_word2wordgroup
{
	public function execute($content){
		$content = strip_tags($content);
		$content = str_replace('&nbsp;', '', $content);
		$wordInfoRows = ml_tool_chineseSegment::segmentWithAttr($content);

		$last = '';
		$group = array();
		$aMark = array(',','.','?','!',':',';','"','\'','，','。', '？', '！', '：', '；', '“', '‘' , '、','”' , '《' , '》');
		$aAttrPass = array('c' , 'uj' , 'r' , 'd' , 'm' , 'p' , 'f' , 'un' , 'q' , 'mt');
		$aUnuseWord = array('把','被','成' , '是' , '了' , '等','着' , '下' , '上','让');
		$aUnusePreWord = array('所');
		foreach ($wordInfoRows as $wordInfo) {


			if(in_array($wordInfo['word'], $aMark) //标点断句
				or in_array($wordInfo['attr'], $aAttrPass)	//无用词性
				or ($last &&  strpos($wordInfo['word'], $last) === 0)	//重复字
				or in_array($wordInfo['word'], $aUnuseWord)	//没啥用的字符
				or mb_substr($last, -1 , 1 , 'utf-8') == mb_substr($wordInfo['word'], 0, 1 , 'utf-8')//重复字 由于用了二分匹配 可能出现 我爱 爱你
				or in_array($last, $aUnusePreWord)
				){
				$last = '';
				continue;
			}
			if($last){
				
					$group[] = $last.''.$wordInfo['word'];
			}
			
			$last = $wordInfo['word'];
		}
		foreach ($group as &$value) {
			$value = strtolower($value);
		}
		$rs = array_count_values($group);
		arsort($rs);
		foreach ($rs as $key => $value) {
			if($value>1){
				$aRs[$key] = $value;
			}
		}
		return $aRs;
	}
}
