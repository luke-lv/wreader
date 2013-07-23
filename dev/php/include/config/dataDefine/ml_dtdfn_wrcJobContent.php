<?php

$jobConf = ml_factory::load_standard_conf('wreader_jobs');
$aJob = array();
foreach ($jobConf as $job_type) {
	foreach ($job_type['jobs'] as $key => $value) {
		$aJob[$key] = $value['name'];
	}
}

global $ML_JOBLEVEL , $ML_RECOMMENDLEVEL;

	return array(
		'table'=>'wrc_jobContent',
		'hash_table' => false,
		'field'=>array(
			'job_id' => array(
				'cn'=>'所属职业',
				'type' => 'enum',
				'enum' => $aJob),
			'level' => array(
				'cn'=>'级别',
				'type' => 'enum',
				'enum' => array_flip($ML_JOBLEVEL)),
			'contentName_taghash' => array(
				'cn'=>'内容名称',
				'type' => 'i'),
			'recommend_level' => array(
				'cn'=>'推荐级别',
				'type' => 'enum',
				'enum' => array_flip($ML_RECOMMENDLEVEL)),
		)
	);
?>