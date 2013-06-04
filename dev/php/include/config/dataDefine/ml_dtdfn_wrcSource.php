<?php
	include_once(SERVER_ROOT_PATH.'/include/config/ml_spider_config.php');
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
			'spider_time' => array(
				'cn'=>'抓取频率',
				'type' => 'enum',
				'enum'=>array(ML_SPIDERTIME_3HOUR=>'3小时',ML_SPIDERTIME_1HOUR=>'1小时',ML_SPIDERTIME_6HOUR=>'6小时',ML_SPIDERTIME_1DAY=>'1天')),
			'spider_type' => array(
				'cn'=>'抓取方式',
				'type' => 'enum',
				'enum'=>array(0=>'RSS')),
		)
	);
?>