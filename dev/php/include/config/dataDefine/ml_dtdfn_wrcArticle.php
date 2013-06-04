<?php
	return array(
		'table'=>'wrc_article',
		'field'=>array(
			'title' => array(
				'cn'=>'文章标题',
				'type' => 's',
				'length'=>100),
			'pub_time' => array(
				'cn'=>'rss',
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
		)
	);
?>