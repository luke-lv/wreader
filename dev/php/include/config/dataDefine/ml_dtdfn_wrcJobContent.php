<?php

$jobConf = ml_factory::load_standard_conf('wreader_jobs');
$aJob = array();
foreach ($jobConf as $job_type) {
	foreach ($job_type['jobs'] as $key => $value) {
		$aJob[$key] = $value['name'];
	}
}

global $ML_JOBLEVEL , $ML_RECOMMENDLEVEL , $ML_TAG_CATEGORY;

	return array(
		'table'=>'wrc_jobContent',
		'hash_table' => false,
		'field'=>array(
			'category' => array(
				'cn'=>'内容领域',
				'type' => 'enum',
				'enum' => array_flip($ML_TAG_CATEGORY)),
			'name' => array(
				'cn'=>'能力名称',
				'type' => 's'),
			'level' => array(
				'cn'=>'级别',
				'type' => 'enum',
				'enum' => array_flip($ML_JOBLEVEL)),
			'contentName_tagid' => array(
				'cn'=>'内容名称',
				'type' => 'i'),
			'contentType_tagid' => array(
				'cn'=>'内容方向',
				'type' => 'i'),
		)
	);
?>