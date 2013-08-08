<?php
define('ML_WR_JOBTYPE_PD', 1);
define('ML_WR_JOBTYPE_TECH', 2);
define('ML_WR_JOBTYPE_OP', 3);


define('ML_WR_JOB_PRODUCT_MANAGER', 10001);
define('ML_WR_JOB_PRODUCT_DESIGNER', 10002);
define('ML_WR_JOB_PRODUCT_INTERACTIVE', 10003);

define('ML_WR_JOB_TECH_PHP', 10101);
define('ML_WR_JOB_TECH_JS', 10102);
define('ML_WR_JOB_TECH_CSS', 10103);
define('ML_WR_JOB_TECH_DBA', 10104);
define('ML_WR_JOB_TECH_SRVOP', 10105);
define('ML_WR_JOB_TECH_IOSDEV', 10106);
define('ML_WR_JOB_TECH_ANDROIDDEV', 10107);
define('ML_WR_JOB_TECH_ARCH', 10108);


return array(
	ML_WR_JOBTYPE_PD => array(
		'id'=>ML_WR_JOBTYPE_PD,
		'name' => '产品系',
		'tag_category' => ML_TAGCATEGORY_PD,
		'jobs' => array(
			ML_WR_JOB_PRODUCT_MANAGER => array(
				'name' => '产品经理',
				'sign' => 'productManager',
			),
			ML_WR_JOB_PRODUCT_DESIGNER => array(
				'name' => '产品设计师',
				'sign' => 'productDesigner',
			),
			ML_WR_JOB_PRODUCT_INTERACTIVE => array(
				'name' => '交互设计师',
				'sign' => 'productInteractive',
			),
		),
	),
	ML_WR_JOBTYPE_TECH => array(
		'id'=>ML_WR_JOBTYPE_TECH,
		'name' => '技术系',
		'tag_category' => ML_TAGCATEGORY_TECH,
		'jobs' => array(
			ML_WR_JOB_TECH_PHP => array(
				'name' => 'php应用开发',
				'sign' => 'php',
			),
			ML_WR_JOB_TECH_JS => array(
				'name' => 'ria开发',
				'sign' => 'js',
			),
			ML_WR_JOB_TECH_CSS => array(
				'name' => '页面构建',
				'sign' => 'css',
			),
			ML_WR_JOB_TECH_DBA => array(
				'name' => 'DBA',
				'sign' => 'dba',
			),
			ML_WR_JOB_TECH_SRVOP => array(
				'name' => '运维',
				'sign' => 'serverOperater',
			),
			ML_WR_JOB_TECH_ARCH => array(
				'name' => '架构师',
				'sign' => 'serverArch',
			),
			ML_WR_JOB_TECH_IOSDEV => array(
				'name' => 'IOS开发',
				'sign' => 'ios',
			),
			ML_WR_JOB_TECH_ANDROIDDEV => array(
				'name' => '安卓开发',
				'sign' => 'android',
			),
		),
	),
);