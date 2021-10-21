<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DownloadImageMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class DownloadImageMessageHandler implements MessageHandlerInterface
{
    public function __construct(private HttpClientInterface $client, private MessageBusInterface $bus, private string $storageDirectory, private LoggerInterface $logger)
    {
    }

    public function __invoke(DownloadImageMessage $message)
    {
        $response = $this->client->request('GET', $message->getUrl());
        $directory = $this->storageDirectory.\DIRECTORY_SEPARATOR.$message->getId();
        if (!is_dir($directory)) {
            mkdir($directory);
        }

        $fileName = $directory.\DIRECTORY_SEPARATOR.basename(parse_url($message->getUrl(), \PHP_URL_PATH));
        file_put_contents(
            $fileName,
            $response->getContent()
        );

        $this->logger->info(sprintf('Wrote "%d" bytes in "%s"', $response->getInfo('size_download'), $fileName));
    }
}
