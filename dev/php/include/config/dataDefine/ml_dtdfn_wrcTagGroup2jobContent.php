<?php
	global $ML_TAG_CATEGORY;
	return array(
		'table'=>'wru_user',
		'hash_table' => false,
		'field'=>array(
			'category' => array(
				'cn'=>'内容领域',
				'type' => 'enum',
				'enum' => array_flip($ML_TAG_CATEGORY)),
			'jobContentId' => array(
				'cn'=>'职业能力',
				'type' => 'i'),
			'contentName_tagid' => array(
				'cn'=>'内容名称',
				'type' => 'i'),
			'tags' => array(
				'cn'=>'辅助标签',
				'type' => 'i'),
		)
	);
?>