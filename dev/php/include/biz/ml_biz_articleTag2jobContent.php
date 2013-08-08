<?php
class ml_biz_articleTag2jobContent
{
	private $metaTag = array();
	public function execute($aTag)
	{
		$this->metaTag = array();
		$oTag = new ml_model_admin_dbTag();
		$oTag->tags_getAllRelation($aTag);
		$aAllRelTag = $oTag->get_data();
		$aAllTag = array_merge($aTag , Tool_array::format_2d_array($aAllRelTag , 'tag' , Tool_array::FORMAT_VALUE_ONLY));
		foreach ($aTag as &$tag) {
			$tag = strtolower($tag);
		}
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

		if(count($dest_contentName) > 0)
		{
			$otg2jc = new ml_model_wrcTagGroup2jobContent();

			foreach ($dest_contentName as $contentName) {
				
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