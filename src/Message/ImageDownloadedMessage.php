<?php

namespace App\Message;

final class ImageDownloadedMessage
{
    public function __construct(private string $id, private int $num) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getNum(): int
    {
        return $this->num;
    }
}
