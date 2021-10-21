<?php

declare(strict_types=1);

namespace App\Message;

final class ZipCreationMessage
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
