<?php

namespace App\MessageHandler;

use App\Message\ImageDownloadedMessage;
use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ZipCreationMessageHandler implements MessageHandlerInterface
{
    private $imagesDownloaded = [];

    public function __invoke(ImageDownloadedMessage $message)
    {
        if (!isset($this->imagesDownloaded[$message->getId()])) {
            $this->imagesDownloaded[$message->getId()] = 0;
        }

        $this->imagesDownloaded[$message->getId()]++;

        dump('Image downloaded', $this->imagesDownloaded[$message->getId()]);

        if ($this->imagesDownloaded[$message->getId()] === $message->getNum()) {
            unset($this->imagesDownloaded[$message->getId()]);
            dump('CREATE ZIP');

        }
    }
}
