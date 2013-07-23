<?php
class ml_biz_articleTag2jobContent
{
	public function execute($aTag)
	{
		$oTag = new ml_model_admin_dbTag();
		$oTag->tags_get_by_tag($aTag);
		$aTagInfo = Tool_array::format_2d_array( $oTag->get_data() , 'id' , Tool_array::FORMAT_FIELD2ROW);

		$dest_contentName = array();


		$aTagExtId = array();
		foreach ($aTagInfo as $row) {
			
			if($row['contentName_tagid'])
				$aContentNameTagId;
		}
	}
}