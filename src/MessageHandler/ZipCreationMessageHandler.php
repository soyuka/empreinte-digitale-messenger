<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ZipCreationMessage;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;
use ZipArchive;

final class ZipCreationMessageHandler implements MessageHandlerInterface
{
    public function __construct(private string $storageDirectory)
    {
    }

    public function __invoke(ZipCreationMessage $message)
    {
        $directory = $this->storageDirectory.\DIRECTORY_SEPARATOR.$message->getId();

        if (!$handle = opendir($directory)) {
            throw new UnrecoverableMessageHandlingException(sprintf('The "%s" directory to Zip does not exist.', $directory));
        }

        $zipName = $this->storageDirectory.\DIRECTORY_SEPARATOR.$message->getId().'.zip';
        $zip = new ZipArchive();
        $zip->open($zipName, ZipArchive::CREATE);

        while (false !== ($entry = readdir($handle))) {
            if ('.' != $entry && '..' != $entry) {
                $zip->addFile($directory.\DIRECTORY_SEPARATOR.$entry);
            }
        }

        closedir($handle);
        $zip->close();

        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->attachFromPath($zipName)
            ->subject('Your ZIP is ready')
            ->text('Check it out!');

        dump($email);
        // $mailer->send($email);
    }
}
