<?php

namespace App\Controller\Forum;

use App\Entity\ForumPost;
use App\Entity\ForumReply;
use App\Entity\ForumTopic;
use App\Entity\Kudos;
use App\Entity\PostKudos;
use App\Entity\User;
use App\Form\ForumCategoryType;
use App\Form\ForumPostType;
use App\Form\ForumReplyType;
use App\Form\ForumTopicType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use App\Repository\ForumReplyRepository;
use App\Repository\ForumTopicRepository;
use App\Repository\KudosRepository;
use App\Repository\PostKudosRepository;
use App\Services\ForumService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * Require ROLE_USER for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 */
class ForumUpdateController extends AbstractController
{
    /**
     * @Route("/forum/categoryUpdate/{catId}", name="forumUpdateCategory")
     */
    public function updateCategory(
        EntityManagerInterface $em,
        Request $request,
        ForumCategoryRepository $categoryRepository,
        string $catId
    ){
        $category = $categoryRepository->findOneBy(['id' => $catId]);

        $now = new \DateTime('now');
        $form = $this->createForm(ForumCategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var ForumPost $post */
            $category = $form->getData();
            $category->setUpdatedAt($now);
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Die Kategorie wurde bearbeitet!');
            return $this->redirectToRoute('forumCategoryView', ['id' => $catId]);
        }
    }

    /**
     * @Route("/forum/topicUpdate/{topicId}", name="forumUpdateTopic")
     */
    public function updateTopic(
        EntityManagerInterface $em,
        Request $request,
        ForumTopicRepository $topicRepository,
        ForumService $forumService,
        string $topicId
    ){
        $topic = $topicRepository->findOneBy(['id' => $topicId]);
        $category = $topic->getCategory();
        $now = new \DateTime('now');
        $form = $this->createForm(ForumTopicType::class, $topic);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var ForumPost $post */
            $topic = $form->getData();
            $topic->setUpdatedAt($now);
            $category->setUpdatedAt($now);

            $em->persist($category);
            $em->persist($topic);
            $em->flush();
            $this->addFlash('success', 'Das Thema wurde bearbeitet!');
            return $this->redirectToRoute('forumTopicView', ['topicId' => $topicId]);
        }
    }

    /**
     * @Route("/forum/postUpdate/{postId}", name="forumUpdatePost")
     */
    public function updatePost(
        EntityManagerInterface $em,
        Request $request,
        ForumPostRepository $postRepository,
        string $postId
    )
    {
        $post = $postRepository->findOneBy(['id' => $postId]);
        $now = new \DateTime('now');
        $form = $this->createForm(ForumPostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var ForumPost $post */
            $post = $form->getData();
            $post->setUpdatedAt($now);
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Der Beitrag wurde bearbeitet!');
            return $this->redirectToRoute('forumTopicView', ['topicId' => $post->getPostTopic()->getId()]);
        }
    }

    /**
     * @Route("forum/replyUpdate/{replyId}", name="forumUpdateReply")
     */
    public function updateReply(
        EntityManagerInterface $em,
        Request $request,
        ForumReplyRepository $replyRepository,
        string $replyId
    )
    {
        $reply = $replyRepository->findOneBy(['id' => $replyId]);
        $now = new \DateTime('now');
        $form = $this->createForm(ForumReplyType::class, $reply);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var ForumReply $reply */
            $reply = $form->getData();
            $reply->setUpdatedAt($now);
            $em->persist($reply);
            $em->flush();
            $this->addFlash('success', 'Die Antwort wurde bearbeitet!');
            return $this->redirectToRoute('forumTopicView', ['topicId' => $reply->getTopic()->getId()]);
        }
    }

    /**
     * @Route("forum/topic/updatePostKudos/{postId}", name="forumUpdatePostKudos")
     */
    public function updatePostKudos(
        EntityManagerInterface $em,
        ForumPostRepository $postRepository,
        PostKudosRepository $postKudosRepository,
        string $postId)
    {

        /** @var ForumPost $post */
        $post = $postRepository->findOneBy(['id'=> $postId]);

        /** @var User $user */
        $user = $this->getUser();

        if(!$postKudosRepository->findOneBy(['post' => $post, 'user' => $user])) {
            /** @var PostKudos $postKudos */
            $postKudos = new PostKudos();
            $now = new \DateTime('now');
            $postKudos->setPost($post);
            $postKudos->setCreatedAt($now);
            $postKudos->setUser($user);

            $em->persist($postKudos);
            $em->flush();
        } else {
            $em->remove($postKudosRepository->findOneBy(['post' => $post, 'user' => $user]));
            $em->flush();
        }

        return new JsonResponse(['kudos' =>  count($post->getPostKudos())]);
    }

}