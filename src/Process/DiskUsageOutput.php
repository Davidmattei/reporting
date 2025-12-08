<?php

declare(strict_types=1);

namespace App\Process;

use Symfony\Component\DependencyInjection\Attribute\Exclude;

/**
 * @implements \IteratorAggregate<array{size: string, path: string}>
 */
#[Exclude]
class DiskUsageOutput implements \IteratorAggregate
{
    public function __construct(
        private readonly string $output,
    ) {
    }

    /**
     * @return \Traversable<array{size: string, path: string}>
     */
    public function getIterator(): \Traversable
    {
        if (false === $lines = preg_split('/\r\n|\r|\n/', $this->output)) {
            throw new \RuntimeException('Split failed');
        }

        foreach ($lines as $line) {
            $parts = explode("\t", $line);
            if (2 !== \count($parts)) {
                continue;
            }

            yield [
                'size' => $parts[0],
                'path' => $parts[1],
            ];
        }
    }
}
