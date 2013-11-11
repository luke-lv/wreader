<?php
	include_once(SERVER_ROOT_PATH.'/include/config/ml_spider_config.php');
	global $ML_TAG_CATEGORY;

	return array(
		'table'=>'wrc_source',
		'hash_table' => false,
		'field'=>array(
			'title' => array(
				'cn'=>'网站名称',
				'type' => 's',
				'length'=>30),
			'codeSign' => array(
				'cn'=>'编程标识',
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
			'site_name' => array(
				'cn'=>'网站名称',
				'type' => 's',
				'length'=>50),
			'tags' => array(
				'cn'=>'标签',
				'type' => 'a'),
			'language' => array(
				'cn'=>'语言',
				'type' => 'enum',
				'enum'=>array(0=>'中文',1=>'英文')),
			'spider_time' => array(
				'cn'=>'抓取频率',
				'type' => 'enum',
				'enum'=>array(ML_SPIDERTIME_3HOUR=>'3小时',ML_SPIDERTIME_1HOUR=>'1小时',ML_SPIDERTIME_6HOUR=>'6小时',ML_SPIDERTIME_1DAY=>'1天',ML_SPIDERTIME_NEVER=>'暂停')),
			'spider_type' => array(
				'cn'=>'抓取方式',
				'type' => 'enum',
				'enum'=>array(0=>'RSS' , 1=>'RSS+页面抓取' , 2=>'RSS+自定义抓取')),
			'charset' => array(
				'cn'=>'编码',
				'type' => 'enum',
				'enum'=>array(ML_CHARSET_UTF8=>'utf8' , ML_CHARSET_GBK=>'gbk')),
			'category' => array(
				'cn'=>'内容分类',
				'type' => 'enum',
				'enum'=>array_flip($ML_TAG_CATEGORY)),
			'contentName_tagid' => array(
				'cn'=>'内容名称',
				'type' => 'enum'),
				
		)
	);
?>