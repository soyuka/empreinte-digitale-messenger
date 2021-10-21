<?php

declare(strict_types=1);

namespace App\Message;

final class DownloadImageMessage
{
    public function __construct(private string $url, private string $id)
    {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
