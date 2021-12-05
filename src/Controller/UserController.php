<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VideoRepository;
use App\Repository\UserRepository;
use App\Repository\VideoSeenRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    #[Route('/tutubeur/{tutubeur_id}', name: 'tutubeur_page')]
    public function tutubeur_page(
        VideoRepository $videoRepository,
        VideoSeenRepository $videoSeenRepository,
        UserRepository $userRepo,
        int $tutubeur_id
    ): Response
    {
        $viewer = false;
        $is_fan = false;

        if ($this->getUser()){
            $user_id = $this->getUser()->getId();
            $viewer = true;
        } else {
            $user_id = 2;
        }

        $tutubeur = $userRepo->find($tutubeur_id);
        $tutubeur_fans = $tutubeur->getFollower()->getValues();
        $tutubeur_fans_id = array_map(fn($fan) => $fan->getId(), $tutubeur_fans);
        
        if(in_array($user_id, $tutubeur_fans_id)){
            $is_fan = true;
        }

        $userVideos = $videoRepository->findByUser($tutubeur_id);
        $userVideoCounter = count($userVideos);

        $videosView = array_map(fn($value) => count($value->getVideoSeens()), $userVideos);
        $userAllView = array_sum($videosView);

        return $this->render('user/tutubeur_page.html.twig', [
            "userVideos" => $userVideos,
            "userId" => $user_id,
            "videoCounter" => $userVideoCounter,
            "viewCounter" => $userAllView,
            "viewer" => $viewer,
            "is_fan" => $is_fan,
            "tutubeur_id" => $tutubeur_id
        ]);
    }

    #[Route('/following', name:"following_page")]
    public function following_page(
        UserRepository $user_repo,
        VideoRepository $video_repo
    ):Response 
    {
        $current_user = $this->getUser();

        $all_following = $current_user->getFollowing()->getValues();
        $all_following_id = array_map(fn($value) => $value->getId(), $all_following);
        $user_following_videos = $video_repo->searchFollowingVideos($all_following_id);
        return $this->render("user/following_page.html.twig", [
            "videos" => $user_following_videos
        ]);
    }

    #[Route('tutubeur/video/delete/{video_id}', name:"tutubeur_video_delete")]
    public function tutubeur_video_delete(
            int $video_id,
            VideoRepository $video_repo,
            EntityManagerInterface $em
        ): Response
    {
        $video_to_delete = $video_repo->find($video_id);
        $tutubeur_id = $video_to_delete->getUser()->getId();

        $em->remove($video_to_delete);
        $em->flush();
        
        return $this->redirectToRoute('tutubeur_page', [
            "tutubeur_id" => $tutubeur_id
        ]);
    }

    #[Route('tutubeur/{action}/{source_id}/{target_id}', name:"tutubeur_follow")]
    public function tutubeur_follow(
        EntityManagerInterface $em,
        UserRepository $user_repo,
        int $source_id,
        int $target_id,
        string $action
    ):Response
    {
        $target_user = $user_repo->find($target_id);
        $source_user = $user_repo->find($source_id);

        if($action === "follow"){
            $target_user->addFollower($source_user);
            $em->persist($target_user);
            $em->flush();
        } else if($action === "unfollow"){
            $target_user->removeFollower($source_user);
            $em->persist($target_user);
            $em->flush();
        }

        return $this->redirectToRoute("tutubeur_page", [
            "tutubeur_id" => $target_id
        ]);

    }


}
