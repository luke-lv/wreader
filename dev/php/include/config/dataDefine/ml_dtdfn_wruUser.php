<?php
	return array(
		'table'=>'wru_user',
		'hash_table' => false,
		'field'=>array(
			
			'nick' => array(
				'cn'=>'昵称',
				'type' => 's',
				'length'=>20),
			'job' => array(
				'cn'=>'工作',
				'type' => 'i'),
			'status' => array(
				'cn'=>'状态',
				'type' => 'enum',
				'enum'=>array(0=>'正常' , 9=>'删除'),
			)
		)
	);
?>