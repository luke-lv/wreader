<?php
class ml_biz_articleTag2jobContent
{
	private $metaTag = array();
	public function execute($aTag)
	{
		foreach ($aTag as &$tag) {
			$tag = strtolower($tag);
		}

		$this->metaTag = array();
		$oTag = new ml_model_admin_dbTag();

		//全部相关标签		
		$oTag->tags_getAllRelation($aTag);
		$aAllRelTag = $oTag->get_data();
		$aTagIndb = Tool_array::format_2d_array($aAllRelTag , 'tag' , Tool_array::FORMAT_VALUE_ONLY);
		$aAllTag = array_merge($aTag , $aTagIndb);	//标签库中存在的标签
		$aOtherTag = array_diff($aTag, $aTagIndb);	//标签库中不存在的标签

		//找到内容名称和内容方向
		foreach ($aAllRelTag as $row){
			if(in_array($row['tag'] , $aTag))
				$this->metaTag[] = $row['tag'];

			if($row['type'] == ML_TAGTYPE_CONTENTNAME)
				$dest_contentName[] = $row;
			if($row['type'] == ML_TAGTYPE_CONTENTTYPE)
				$dest_contentType[] = $row;
		}

		$oJobContent = new ml_model_wrcJobContent();
		$aJobContentId = array();

		//
		

		if(count($dest_contentName) > 0)
		{
			//直接用内容名称和内容方向找职业能力
			foreach ($dest_contentName as $namerow) {
				foreach ($dest_contentType as $typerow) {
					$oJobContent->get_by_name_type($namerow['id'] , $typerow['id']);
					$job_content = $oJobContent->get_data();
					if($job_content['id'])
						$aJobContentId[] = $job_content['id'];
				}
			}

			if($aJobContentId)
				return $aJobContentId;




			$otg2jc = new ml_model_wrcTagGroup2jobContent();

			//查找subtags库，看是否能转换
			foreach ($dest_contentName as $contentName) {
				
				$aSubtag2tag = ml_factory::load_standard_conf('subtags_'.$contentName['tag']);
				if(is_array($aSubtag2tag)){
					foreach ($aSubtag2tag as $sub2tagConf) {
						foreach ($aTag as $metatag) {
							
							if(in_array($metatag, $sub2tagConf['subtags'])){
								$aAllTag[] = $sub2tagConf['contentType_tag'];
							}
						}					
					}
					
				}




				$otg2jc->get_by_contentName_tagid($contentName['id']);
				$rows = $otg2jc->get_data();

				if(!empty($rows)){
					foreach ($rows as $cn_row) {
						$aAddTags = $cn_row['tags'];
	
						foreach ($aAllTag as $tag) {
							if(in_array($tag, $aAddTags)){
								$aJobContentId[] = $cn_row['jobContentId'];
							}
						}
					}
				}
			}
			
		}

		return $aJobContentId;
	}
	public function getMetaTag()
	{
		return $this->metaTag;
	}
}