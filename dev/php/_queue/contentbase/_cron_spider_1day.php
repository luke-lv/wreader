<?php
include('./_cron_spider_base.php');

$o = new _cron_spider_base(ML_SPIDERTIME_1DAY);
$o->execute();

