<?php

namespace App\Controller;

use App\Form\VideoFormType;
use App\Entity\Video;
use App\Entity\User;
use App\Entity\VideoSeen;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    #[Route('/video/{video_id}', name: 'video_page')]
    public function video_page(Request $request, int $video_id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Video::class);
        $video = $repo->find($video_id);

        $user_id = $this->getUser()->getId();

        $video_url = $video->getVideoUrl();

        $viewsRepo = $this->getDoctrine()->getRepository(VideoSeen::class);
        $views = $viewsRepo->viewCounter($video_id)[0][1];

        return $this->render("video/video.html.twig", [
            "video" => $video,
            "video_url" => $video_url,
            "views" => $views,
            "userId" => $user_id
        ]);
    }

    #[Route('/video/create', name: 'create_video')]
    public function create_video(Request $request): Response
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('login');
        }

        $video = new Video();

        $videoForm = $this->createForm(VideoFormType::class, $video);
        $videoForm->handleRequest($request);

        if($videoForm->isSubmitted() && $videoForm->isValid()){
            $video->setUser($user);
            $video_url = $video->getVideoUrl();
            $video_url = str_replace("watch?v=", "embed/", $video_url);
            $video->setVideoUrl($video_url);
            $videoData = $videoForm->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('create_video');
        }

        return $this->renderForm('video/create.html.twig', [
            'videoForm' => $videoForm,
        ]);
    }

    #[Route('/video/update', name: 'update_video')]
    public function update_video(Request $request, int $video_id): Response
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('login');
        }

        $repo = $this->getDoctrine()->getRepository(Video::class);
        $video = $repo->find($video_id);

        $updateForm = $this->createForm(VideoFormType::class, $video);
        $updateForm->handleRequest($request);

        if($updateForm->isSubmitted() && $updateForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $updateData = $updateForm->getData();
            $em->persist($updateData);
            $em->flush();

            return $this->redirectToRoute('update_video');
        }

        return $this->renderForm('video/update.html.twig', [
            "videoUpdate" => $updateForm
        ]);
    }

    #[Route('/video/see/{video_id}/{user_id}', name: 'see_vieo')]
    public function see_vieo(Request $request, int $user_id, int $video_id): Response
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($user_id);

        $videoRepo = $this->getDoctrine()->getRepository(Video::class);
        $video = $videoRepo->find($video_id);

        $view = new VideoSeen();
        $view->setUser($user);
        $view->setVideo($video);

        $em = $this->getDoctrine()->getManager();
        $em->persist($view);
        $em->flush();

        return $this->redirectToRoute('video_page', [
            "video_id" => $video_id
        ]);
    }
}
