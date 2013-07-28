<?php
class ml_biz_articleTag2jobContent
{
	public function execute($aTag)
	{
		$oTag = new ml_model_admin_dbTag();
		$oTag->tags_get_by_tag($aTag);
		$aTagInfo = $oTag->get_data();

		$dest_contentName = array();


		$aTagExtId = array();
		foreach ($aTagInfo as $row) {
			
			if($row['contentName_tagid'])
				$aTagExtId[] = $row['contentName_tagid'];
			if($row['core_tagid'])
				$aTagExtId[] = $row['core_tagid'];
		}

		$aTagExtId = array_unique($aTagExtId);

		$oTag->tags_get_by_ids($aTagExtId);
		$aTagInfo = array_merge($oTag->get_data() , $aTagInfo);

		foreach ($aTagInfo as $row) {

			if($row['type'] == ML_TAGTYPE_CONTENTNAME)
				$dest_contentName[] = $row;
			else if($row['type'] == ML_TAGTYPE_CONTENTTYPE)
				$dest_contentType[] = $row;

			if($row['contentName_tagid'])
				$aTagExtId2[] = $row['contentName_tagid'];
			if($row['core_tagid'])
				$aTagExtId2[] = $row['core_tagid'];
		}

		$oTag->tags_get_by_ids($aTagExtId2);
		$aTagInfo = array_merge($oTag->get_data() , $aTagInfo);

		foreach ($aTagInfo as $row) {

			if($row['type'] == ML_TAGTYPE_CONTENTNAME)
				$dest_contentName[] = $row;
			else if($row['type'] == ML_TAGTYPE_CONTENTTYPE)
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
var_dump($aJobContentId);
		if($aJobContentId)
			return $aJobContentId;

		$aAllTagid = Tool_array::format_2d_array($aTagInfo , 'id' , Tool_array::FORMAT_VALUE_ONLY);
		
		foreach ($aAllTagid as $tag_1) {
			foreach ($aAllTagid as $tag_2) {
				if($tag_2 == $tag_1)
					continue;
				$aTagCombine[] = $tag_1 > $tag_2 
								? ($tag_2.'_'.$tag_1) 
								: ($tag_1.'_'.$tag_2);
			}
		}
		$aTagCombine = array_unique($aTagCombine);

		$oTag2jc = new ml_model_wrcTag2jobContent();
		foreach ($aTagCombine as $value) {
			$oTag2jc->getByTagid(explode('_', $value));
			$t2jc_row = $oTag2jc->get_data();
			if(!empty($t2jc_row))
			{
				$aJobContentId[] = $t2jc_row['jobContentId'];
			}
		}
		return $aJobContentId;
	}
}