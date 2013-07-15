<?php
	return array(
		'table'=>'wru_userSetting',
		'hash_table' => false,
		'field'=>array(
			
			'uid' => array(
				'cn'=>'uid',
				'type' => 'i'),
			'type' => array(
				'cn'=>'类型',
				'type' => 'enum',
				'enum'=>array(1=>'手机游览器版显示设置')),
			'data' => array(
				'cn'=>'状态',
				'type' => 'text'),
		)
	);
?>