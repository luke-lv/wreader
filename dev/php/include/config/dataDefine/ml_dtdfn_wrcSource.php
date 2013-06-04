<?php
	return array(
		'table'=>'wrc_source',
		'field'=>array(
			'title' => array(
				'cn'=>'网站名称',
				'type' => 's',
				'length'=>30),
			'rss' => array(
				'cn'=>'rss',
				'type' => 's',
				'length'=>200,
				'format'=>'url'),
			'domain' => array(
				'cn'=>'域名',
				'type' => 's',
				'length'=>50),
			'language' => array(
				'cn'=>'语言',
				'type' => 'enum',
				'enum'=>array(0=>'中文',1=>'英文')),
		)
	);
?>