<?php
class ml_tool_jobs
{
	static public function getJobConf($job_id)
	{
		$aJobs = self::list_all_job();
		return $aJobs[$job_id];
	}

	static public function get_job_category($job_id)
	{
		$aJobs = self::list_all_job();
		return $aJobs[$job_id]['category'];
	}

	static public function list_all_job()
	{
		$job_conf = ml_factory::load_standard_conf('wreader_jobs');
		foreach ($job_conf as $type) {
			foreach ($type['jobs'] as $key => $value) {
				$value['category'] = $type['tag_category'];
				$aJobs[$key] = $value;
			}
		}
		return $aJobs;
	}
}