<?php

namespace App\Message;

final class DownloadImageMessage
{
    public function __construct(private string $url, private string $id, private int $num) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getNum(): int
    {
        return $this->num;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
