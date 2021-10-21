<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Message\ZipCreationMessage;
use App\Stamp\ImageDownloadStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;

class ImagesDownloadedMiddleware implements MiddlewareInterface
{
    private array $handles = [];

    public function __construct(private string $storageDirectory)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if ($envelope->last(ErrorDetailsStamp::class) || !($stamp = $envelope->last(ImageDownloadStamp::class)) || $stamp->isDownloaded()) {
            return $stack->next()->handle($envelope, $stack);
        }

        if (!$stamp->isDownloading()) {
            $envelope = $envelope->with($stamp->withDownloading(1));

            return $stack->next()->handle($envelope, $stack);
        }

        if (!flock($handle = $this->getHandle($stamp), LOCK_EX)) {
            usleep(1e+6);
            return $this->handle($envelope, $stack);
        }

        rewind($handle);
        $v = (int) stream_get_contents($handle);

        // We downloaded every file
        if (++$v === $stamp->getNum()) {
            $v = 0;
            $stack->next()->handle(new Envelope(new ZipCreationMessage($stamp->getId())), $stack);
        }

        rewind($handle);
        fwrite($handle, (string) $v);
        flock($handle, LOCK_UN);

        $envelope = $envelope->with($stamp->withDownloading(2));

        return $stack->next()->handle($envelope, $stack);
    }

    public function __destruct()
    {
        foreach ($this->handles as $handle) {
            fclose($handle);
        }
    }

    private function getHandle(ImageDownloadStamp $stamp)
    {
        $handle = $this->handles[$stamp->getId()] ?? null;
        if (!$handle) {
            $handle = $this->handles[$stamp->getId()] = fopen($this->storageDirectory.'/flock-'.$stamp->getId(), 'c+');
        }

        return $handle;
    }
}
