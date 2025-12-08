<?php

declare(strict_types=1);

namespace App\Process;

use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\Process\Process;

#[Exclude]
class DiskUsageProcess
{
    public function __invoke(string $directory): DiskUsageOutput
    {
        $process = new Process(['du', '--bytes', '--all', $directory]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('du failed: '.$process->getErrorOutput());
        }

        return new DiskUsageOutput($process->getOutput());
    }
}
