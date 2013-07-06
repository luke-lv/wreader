<?php
include(dirname(__FILE__).'/_cron_spider_base.php');

$o = new _cron_spider_base();
$o->set_spider_time(ML_SPIDERTIME_1HOUR);
$o->execute();

