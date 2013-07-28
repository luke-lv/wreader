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
			'jobContentIds' => array(
				'cn'=>'能力',
				'type' => 's',
			)
		)
	);
?>