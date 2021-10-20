<?php

namespace App\MessageHandler;

use App\Message\DownloadImageMessage;
use App\Message\ImageDownloadedMessage;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class DownloadImageMessageHandler implements MessageHandlerInterface
{
    public function __construct(private HttpClientInterface $client, private MessageBusInterface $bus, private string $storageDirectory) {}

    public function __invoke(DownloadImageMessage $message)
    {
        $response = $this->client->request('GET', $message->getUrl());
        $name = basename(parse_url($message->getUrl(), PHP_URL_PATH));
        file_put_contents(
            $this->storageDirectory . DIRECTORY_SEPARATOR . $name,
            $response->getContent()
        );

        $this->bus->dispatch(new ImageDownloadedMessage(id: $message->getId(), num: $message->getNum()));
    }
}
