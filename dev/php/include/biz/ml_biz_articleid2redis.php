<?php
class ml_biz_articleid2redis
{
	private $oRdsCB;
	private $oTag;
	private $oArticle;

	public function __construct()
	{
		$this->oRdsCB  = new ml_model_rdsContentBase();
		$this->oTag = new ml_model_admin_dbTag();
		$this->oArticle = new ml_model_wrcArticle();
	}
	public function execute($article_id , $aTag)
	{
        $aTag = array_filter($aTag);
        if(empty($aTag))
            return false;

        $this->oTag->core_tag_get_by_tags($aTag);
        $aCoreTag = $this->oTag->get_data();

        if(!empty($aCoreTag))
        {
            foreach ($aCoreTag as $key => $value) {
            	$this->oArticle->std_getRowById($article_id);
            	$aArticle = $this->oArticle->get_data();

            	$rank = ml_tool_hotrank::calc_hotrank(strtotime($aArticle['pub_time']) , mt_rand(1,2000), mt_rand(1,2000), mt_rand(1,2000));
echo $aArticle['pub_time'].'---'.$rank."<br/>";
                $this->oRdsCB->addArticleToTag($value['tag_hash'] , $article_id , $rank);
            }
        }
	}
}