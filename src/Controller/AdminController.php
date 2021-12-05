<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\VideoRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserUpdateFormType;
use Symfony\Component\String\Slugger\SluggerInterface;


class AdminController extends AbstractController
{
    #[Route('/admin/videos', name: 'admin_videos')]
    public function admin_videos(
        VideoRepository $video_repo,
        UserRepository $user_repo
    ): Response
    {

        $all_videos = $video_repo->findAll();

        $all_user = $user_repo->findAll();

        return $this->render('admin/videos.html.twig', [
            "videos"=>$all_videos,
            "users" => $all_user
        ]);
    }

    #[Route('/admin/video/delete/{video_id}', name:"admin_delete_video")]
    public function admin_delete_video(
        VideoRepository $video_repo,
        int $video_id,
        EntityManagerInterface $em
    ):Response
    {
        $video_to_delete = $video_repo->find($video_id);

        $em->remove($video_to_delete);
        $em->flush();

        return $this->redirectToRoute('admin_videos');
    }

    #[Route('/admin/video/{video_id}', name:"admin_video_page")]
    public function admin_video_page(
        int $video_id,
        VideoRepository $video_repo
    ):Response
    {
        $video = $video_repo->find($video_id);
        return $this->render("/admin/video.html.twig", [
            "video" => $video
        ]);
    }

    #[Route('/admin/user/delete/{user_id}', name:"admin_delete_user")]
    public function admin_delete_user(
        UserRepository $user_repo,
        int $user_id,
        EntityManagerInterface $em
    ):Response
    {
        $user_to_delete = $user_repo->find($user_id);
        $em->remove($user_to_delete);
        $em->flush();

        return $this->redirectToRoute('admin_videos');
    }

    #[Route('/admin/user/update/{user_id}', name:'admin_update_user')]
    public function admin_update_user(
        int $user_id,
        UserRepository $user_repo,
        EntityManagerInterface $em,
        Request $request,
        SluggerInterface $slugger
    ):Response
    {   
        $user_to_update = $user_repo->find($user_id);

        $form = $this->createForm(UserUpdateFormType::class, $user_to_update);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('profil_image')->getData();
            $user = $form->getData();
            if($file){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('profil_image'),
                        $newFilename
                    );
                }catch (FileException $e){

                }
                $user->setProfileImage($newFilename);
            }
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("admin_videos");
        }

        return $this->renderForm("admin/user_update.html.twig", [
            "form" => $form
        ]);
    }

    #[Route('/admin/comment/delete/{video_id}/{comment_id}', name:("admin_comment_delete"))]
    public function admin_comment_delete(
        CommentRepository $comment_repo,
        EntityManagerInterface $em,
        int $comment_id,
        int $video_id,
        Request $request
    ):Response
    {
        $comment_to_delete = $comment_repo->find($comment_id);
        $em->remove($comment_to_delete);
        $em->flush();

        return $this->redirectToRoute("admin_video_page", [
            "video_id" => $video_id
        ]);
    }
}
