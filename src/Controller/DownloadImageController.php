<?php

declare(strict_types=1);

namespace App\Controller;

use App\Message\DownloadImageMessage;
use App\Stamp\ImageDownloadStamp;
// use App\Message\ZipCreationMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class DownloadImageController extends AbstractController
{
    #[Route('/', name: 'download_image')]
    public function index(): Response
    {
        $images = [
            'http://www.serebii.net/pokemongo/pokemon/001.png',
            'http://www.serebii.net/pokemongo/pokemon/002.png',
            'http://www.serebii.net/pokemongo/pokemon/003.png',
            'http://www.serebii.net/pokemongo/pokemon/004.png',
            'http://www.serebii.net/pokemongo/pokemon/005.png',
            'http://www.serebii.net/pokemongo/pokemon/006.png',
            'http://www.serebii.net/pokemongo/pokemon/007.png',
            'http://www.serebii.net/pokemongo/pokemon/008.png',
            'http://www.serebii.net/pokemongo/pokemon/009.png',
            'http://www.serebii.net/pokemongo/pokemon/010.png',
        ];

        $id = Uuid::v4();
        $num = \count($images);

        foreach ($images as $image) {
            $this->dispatchMessage(new DownloadImageMessage(url: $image, id: $id), [
                new ImageDownloadStamp(id: $id, num: $num),
            ]);
        }

        return $this->render('download_image/index.html.twig', [
            'controller_name' => 'DownloadImageController',
        ]);
    }
}
