<?php

declare(strict_types=1);

namespace App\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

final class ImageDownloadStamp implements StampInterface
{
    public function __construct(private string $id, private int $num, private int $downloading = 0)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNum(): int
    {
        return $this->num;
    }

    public function isDownloading(): bool
    {
        return 1 === $this->downloading;
    }

    public function isDownloaded(): bool
    {
        return 2 === $this->downloading;
    }

    public function withDownloading(int $downloading): self
    {
        $self = clone $this;
        $self->downloading = $downloading;

        return $self;
    }
}
