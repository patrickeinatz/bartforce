<?php

namespace App\Controller\Forum;

use App\Entity\ForumPost;
use App\Entity\ForumTopic;
use App\Form\ForumCategoryType;
use App\Form\ForumPostType;
use App\Form\ForumTopicType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use App\Repository\ForumTopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
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
}