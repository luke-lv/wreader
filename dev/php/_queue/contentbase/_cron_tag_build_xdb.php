<?php
include(dirname(dirname(dirname(__FILE__))).'/__global.php');
include(SERVER_ROOT_PATH.'/include/config/ml_spider_config.php');


class _cron_tag_build_xdb
{
	private $oAdminCommon;
	public function __construct()
	{
		$this->oAdminCommon = new ml_model_admin_dbCommon();
	}

	public function execute()
	{
		$this->oAdminCommon->tags_getAll();
		$rows = $this->oAdminCommon->get_data();

		//$tags = Tool_array::format_2d_array($rows , 'tag' , Tool_array::FORMAT_VALUE_ONLY);

		$fp = fopen('tags_build.tmp', 'w');
		foreach ($rows as $row) {
			$idf = 13.8*$row['segment_idf'];

			fwrite($fp, $row['tag']."\t13.8\t".$idf."\tsn\n");
		}
		fclose($fp);
		

		$dest_tmp = SERVER_ROOT_PATH.'/include/config/scws/wreader_tmp.xdb';
		$dest = SERVER_ROOT_PATH.'/include/config/scws/wreader.xdb';
		unlink($dest_tmp);

		$cmd = 'php '.SERVER_ROOT_PATH.'/__admin/other/scws/make_xdb_file.php '. $dest_tmp . ' '.SERVER_ROOT_PATH.'/_queue/contentbase/tags_build.tmp';
		echo $cmd."\n";
		$rs = Tool_os::run_cmd($cmd);
		if(trim(substr($rs, -6)) == 'DONE!')
		{

			unlink($dest);
			$rs = rename($dest_tmp, $dest);
			if($rs)
				echo 'build ok!';
		}
		die('x');


	}

}
$o = new _cron_tag_build_xdb();
$o->execute();