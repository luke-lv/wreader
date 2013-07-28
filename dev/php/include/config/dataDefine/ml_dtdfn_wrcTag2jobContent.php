<?php
	global $ML_TAL_CATEGORY;
	return array(
		'table'=>'wru_user',
		'hash_table' => false,
		'field'=>array(
			'category' => array(
				'cn'=>'内容领域',
				'type' => 'enum',
				'enum' => array_flip($ML_TAL_CATEGORY)),
			'jobContentId' => array(
				'cn'=>'职业能力',
				'type' => 'i'),
			'tagid_1' => array(
				'cn'=>'标签1',
				'type' => 'i'),
			'tagid_2' => array(
				'cn'=>'标签2',
				'type' => 'i'),
		)
	);
?>