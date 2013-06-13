<?php
include('../../__global.php');
include(SERVER_ROOT_PATH.'/include/config/ml_spider_config.php');
include(SERVER_ROOT_PATH.'/include/ml_function_lib.php');

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

		$tags = Tool_array::format_2d_array($rows , 'tag' , Tool_array::FORMAT_VALUE_ONLY);

		$fp = fopen('tags_build.tmp', 'w');
		foreach ($tags as $value) {
			fwrite($fp, $value."\t13.8\t13.8\tsn\n");
		}
		fclose($fp);

		$cmd = 'php '.SERVER_ROOT_PATH.'/__admin/other/scws/make_xdb_file.php '.SERVER_ROOT_PATH.'/xx.xdb ./_queue/contentbase/tags_build.tmp';
		echo $cmd;
	}

}
$o = new _cron_tag_build_xdb();
$o->execute();