<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Transaction
{
    private int $batchSize = 500;
    private int $counter = 0;
    private bool $active = false;

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function begin(int $batchSize): void
    {
        if ($this->active) {
            throw new \LogicException('Transaction already started.');
        }

        $this->batchSize = $batchSize;
        $this->counter = 0;
        $this->active = true;
        $this->em->beginTransaction();
    }

    public function add(object $entity): void
    {
        if (!$this->active) {
            throw new \LogicException('Transaction not started.');
        }

        $this->em->persist($entity);
        ++$this->counter;

        if (0 === $this->counter % $this->batchSize) {
            $this->em->flush();
            $this->em->clear();
        }
    }

    public function commit(): void
    {
        if (!$this->active) {
            throw new \LogicException('Transaction not started.');
        }

        try {
            $this->em->flush();
            $this->em->commit();
        } catch (\Throwable $e) {
            $this->em->rollback();
            throw $e;
        } finally {
            $this->active = false;
        }
    }
}
