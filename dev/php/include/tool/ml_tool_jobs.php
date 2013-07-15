<?php
class ml_tool_jobs
{
	static public function getJobConf($job_id)
	{
		static $jobid2cnf;
		if(!is_array($jobid2cnf))
		{
			$conf = ml_factory::load_standard_conf('wreader_jobs');
			foreach ($conf as $jobtype) {
				foreach ($jobtype['jobs'] as $key => $value) {
					$jobid2cnf[$key] = $value;
				}
			}
		}

		return $jobid2cnf[$job_id];
	}
}