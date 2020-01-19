<?php

namespace App\Controller\Forum;

use App\Entity\ForumPost;
use App\Entity\ForumTopic;
use App\Entity\Kudos;
use App\Entity\PostKudos;
use App\Entity\TopicKudos;
use App\Entity\User;
use App\Form\ForumCategoryType;
use App\Form\ForumPostType;
use App\Form\ForumTopicType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use App\Repository\ForumTopicRepository;
use App\Repository\KudosRepository;
use App\Repository\PostKudosRepository;
use App\Repository\TopicKudosRepository;
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

            if($topic->getTopicContentModule()->getTitle() === 'image'){
                $topic->setTopicContent(
                    $forumService->makeImageLink($topic->getTopicContent())
                );
            }

            if($topic->getTopicContentModule()->getTitle() === 'video'){
                $topic->setTopicContent(
                    $forumService->makeYouTubeEmbedLink($topic->getTopicContent())
                );
            }

            $em->persist($category);
            $em->persist($topic);
            $em->flush();
            $this->addFlash('success', 'Das Thema wurde bearbeitet!');
            return $this->redirectToRoute('forumTopicView', ['topicId' => $topicId]);
        }
    }

    /**
     * @Route("/forum/topicUpdate/{topicId}/redirectRoute", name="forumUpdateTopicRedirect")
     */
    public function updateTopicRedirect(
        EntityManagerInterface $em,
        Request $request,
        ForumTopicRepository $topicRepository,
        string $topicId
    ){
        /** @var ForumTopic $topic */
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
            return $this->redirectToRoute('forumCategoryView', ['id' => $topic->getCategory()->getId()]);
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
     * @Route("forum/topic/updateTopicKudos/{topicId}", name="forumUpdateTopicKudos")
     */
    public function updateTopicKudos(
        EntityManagerInterface $em,
        ForumTopicRepository $topicRepository,
        TopicKudosRepository $topicKudosRepository,
        string $topicId)
    {

        /** @var ForumTopic $topic */
       $topic = $topicRepository->findOneBy(['id'=>$topicId]);

        /** @var User $user */
        $user = $this->getUser();

        if(!$topicKudosRepository->findOneBy(['topic' => $topic, 'user' => $user])) {
            /** @var TopicKudos $kudos */
            $topicKudos = new TopicKudos();
            $now = new \DateTime('now');
            $topicKudos->setTopic($topic);
            $topicKudos->setCreatedAt($now);
            $topicKudos->setUser($user);

            $em->persist($topicKudos);
            $em->flush();
        } else {
            $em->remove($topicKudosRepository->findOneBy(['topic' => $topic, 'user' => $user]));
            $em->flush();
        }

        return new JsonResponse(['kudos' =>  count($topic->getTopicKudos())]);

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