<?php
include('./__global.php');
$o = new Lib_lastRss();
var_dump($o->Get('http://www.36kr.com/feed'));