<?php

namespace App\Controller;

use App\Form\VideoFormType;
use App\Form\CommentFormType;
use App\Entity\Video;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\VideoSeen;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
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

    #[Route('/video/delete/{video_id}', name : "delete_video")]
    public function delete_video(
        Request $request, 
        int $video_id,
        VideoRepository $videoRepo 
    ): Response
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();
        $videoToDelete = $videoRepo->find($video_id);

        $em->remove($videoToDelete);
        $em->flush();

        return $this->redirectToRoute("video_page");
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

    #[Route('/video/{video_id}', name: 'video_page')]
    public function video_page(Request $request, int $video_id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Video::class);
        $video = $repo->find($video_id);

        if ($this->getUser()){
            $user_id = $this->getUser()->getId();
        } else {
            $user_id = 5;
        }

        $video_url = $video->getVideoUrl();

        $viewsRepo = $this->getDoctrine()->getRepository(VideoSeen::class);
        $views = $viewsRepo->viewCounter($video_id)[0][1];
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        $commentRepo = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentRepo->findByVideo($video_id);
        
        if($commentForm->isSubmitted() && $commentForm->isValid()){

            $userRepo = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepo->find($user_id);

            $comment->setUser($user);
            $comment->setVideo($video);

            $commentData = $commentForm->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentData);
            $em->flush();

            return $this->redirectToRoute('video_page', [
                "video_id" => $video_id
            ]);

        }
        
        return $this->renderForm("video/video.html.twig", [
            "video" => $video,
            "video_url" => $video_url,
            "views" => $views,
            "userId" => $user_id,
            "comments" => $comments,
            "commentForm" => $commentForm,
        ]);
    }
}
