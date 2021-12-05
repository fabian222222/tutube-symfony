<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Video;

class HomepageController extends AbstractController
{
    #[Route('/homepage', name: 'homepage')]
    public function homepage() {
        $repo = $this->getDoctrine()->getRepository(Video::class);
        $lastTwentyVideos = $repo->LastTwentyVideo();

        return $this->render('homepage/homepage.html.twig', [
            "videos" => $lastTwentyVideos
        ]);
    }

    #[Route('/homepage/search', name: 'homepage_search')]
    public function homepage_search() {
        $repo = $this->getDoctrine()->getRepository(Video::class);
        $search = $repo->searchVideos($_POST['search']);
        return $this->render('homepage/search_video.html.twig', [
            "videos" => $search,
        ]);
    }
}