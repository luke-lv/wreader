<?php
	return array(
		'table'=>'wrc_article',
		'hash_table' => true,
		'field'=>array(
			'title' => array(
				'cn'=>'文章标题',
				'type' => 's',
				'length'=>100),
			'pub_time' => array(
				'cn'=>'发布时间',
				'type' => 's',
				'length'=>20),
			'summary' => array(
				'cn'=>'摘要',
				'type' => 's',
				'length'=>50),
			'link' => array(
				'cn'=>'文章链接',
				'type' => 's',
				'length'=>50,
				'format' => 'url'),
			'tags' => array(
				'cn'=>'标签',
				'type' => 's',
				'length'=>50),
			'jobContentId' => array(
				'cn'=>'职业能力',
				'type' => 's',
				'length'=>50),
		)
	);
?>