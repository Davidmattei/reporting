<?php

namespace App\Entity;

use App\Repository\FileStatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FileStatRepository::class)]
#[ORM\Table(name: 'file_stats')]
#[ORM\Index(name: 'idx_file_stats_path', columns: ['path'])]
#[ORM\Index(name: 'idx_file_stats_size', columns: ['size'])]
class FileStat
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    private string $id;
    #[ORM\Column]
    private bool $directory;

    public function __construct(
        #[ORM\Column(length: 500)]
        private readonly string $path,
        #[ORM\Column(type: 'string', columnDefinition: 'BIGINT')]
        private readonly string $size,
    ) {
        $this->id = (string) Uuid::v4();
        $this->directory = is_dir($this->path);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function isDirectory(): ?bool
    {
        return $this->directory;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }
}
