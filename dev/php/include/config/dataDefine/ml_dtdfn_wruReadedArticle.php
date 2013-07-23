<?php
	return array(
		'table'=>'wru_readedArticle',
		'hash_table' => false,
		'field'=>array(
			
			'uid' => array(
				'cn'=>'uid',
				'type' => 'i'),
			'article_id' => array(
				'cn'=>'文章ID',
				'type' => 's',
				'length'=>20),
			'article_title' => array(
				'cn'=>'文章标题',
				'type' => 's',
				'length'=>100),
			'my_tag' => array(
				'cn'=>'标签',
				'type' => 's',
				'length'=>20),
			'status' => array(
				'cn'=>'状态',
				'type' => 'enum',
				'enum'=>array(0=>'正常' , 9=>'删除'),
			)
		)
	);
?>