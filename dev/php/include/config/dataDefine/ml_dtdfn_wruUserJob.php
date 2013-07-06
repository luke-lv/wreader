<?php
	return array(
		'table'=>'wru_userjob',
		'hash_table' => false,
		'field'=>array(
			'uid' => array(
				'cn'=>'uid',
				'type' => 'i'
			),
			'job_id' => array(
				'cn'=>'工作',
				'type' => 'i'
			),
			'level' => array(
				'cn'=>'级别',
				'type' => 'i'
			),
			'attend_tag' => array(
				'cn'=>'关注标签',
				'type' => 's',
				'length'=>50,
			),
		)
	);
?>