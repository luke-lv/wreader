<?php
if($_GET['uid'])
{
	setcookie('uid' , $_GET['uid']);
	header('Location:./wrh5m_settingPerson.php');
}
?>
<a href="?uid=1">php工程师</a><br/>
<a href="?uid=2">产品设计</a><br/>
<a href="?uid=3">dba</a><br/>