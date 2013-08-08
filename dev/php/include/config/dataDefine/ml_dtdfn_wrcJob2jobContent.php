<?php

	$aJobs = ml_tool_jobs::list_all_job();
	foreach ($aJobs as $key => $value) {
		$aJobName[$key] = $value['name'];
	}
	return array(
		'table'=>'wrc_job2jobContent',
		'hash_table' => false,
		'field'=>array(
			
			'job_id' => array(
				'cn'=>'工作',
				'type' => 'enum',
				'enum' => $aJobName
				),
			'level' => array(
				'cn'=>'级别',
				'type' => 'enum',
				'enum' => array('0' => '初级','1' => '中级','2' => '高级',)
				),
			'jobContentIds' => array(
				'cn'=>'能力',
				'type' => 's',
			)
		)
	);
?>