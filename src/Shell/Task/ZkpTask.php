<?php
namespace CvoTechnologies\Gearman\Shell\Task;

use Cake\Console\Shell;

class ZkpTask extends Shell
{

    public function main($workload, GearmanJob $job)
    {
        $job->sendStatus(0, 3);

        sleep($workload['timeout']);

        $job->sendStatus(1, 3);

        sleep($workload['timeout']);

        $job->sendStatus(2, 3);

        sleep($workload['timeout']);

        return array(
            'total_timeout' => $workload['timeout'] * 3
        );
    }
}