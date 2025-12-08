<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\FileStat;
use App\Process\DiskUsageProcess;
use App\Service\Transaction;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'report:usage',
    description: 'Add a short description for your command',
)]
class UsageCommand
{
    public function __construct(
        private readonly Transaction $transaction,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument] string $path,
    ): int {
        $io->title('Report usage');

        if (false === $directory = realpath($path)) {
            throw new \RuntimeException(sprintf('Path "%s" does not exist.', $path));
        }

        $this->transaction->begin(batchSize: 500);
        $progress = $io->createProgressBar();

        $du = new DiskUsageProcess();
        foreach ($du($directory) as $info) {
            $this->transaction->add(new FileStat(
                path: $info['path'],
                size: $info['size']
            ));
            $progress->advance();
        }

        $this->transaction->commit();
        $progress->finish();
        $io->newLine(2);

        return Command::SUCCESS;
    }
}
