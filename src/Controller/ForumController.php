<?php

namespace App\Controller;

use App\Entity\ForumCategory;
use App\Entity\ForumPost;
use App\Entity\ForumTopic;
use App\Entity\User;
use App\Form\ForumCategoryType;
use App\Form\ForumPostType;
use App\Form\ForumTopicType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use App\Repository\ForumReplyRepository;
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
class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forumView")
     */
    public function forumView(
        EntityManagerInterface $em,
        Request $request,
        ForumCategoryRepository $repository
    ){
        $categories = $repository->findAll();

        $form = $this->createForm(ForumCategoryType::class);
        $form->handleRequest($request);
        $now = new \DateTime();

        if($form->isSubmitted() && $form->isValid()){
            /** @var ForumCategory $forumCategory */
            $forumCategory = $form->getData();
            $forumCategory->setCreatedAt($now);
            $forumCategory->setUpdatedAt($now);
            $em->persist($forumCategory);
            $em->flush();
            $this->addFlash('success', 'Forum Category added!');
            return $this->redirectToRoute('forumView');
        }

        return $this->render('forum/forumView.html.twig', [
            'title' => 'Forum',
            'categories' => $categories,
            'forumCategoryForm' => $form->createView()
        ]);
    }

    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param ForumTopicRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     * @Route("/forum/category/{id}", name="forumCategoryView")
     */
    public function categoryView(
        EntityManagerInterface $em,
        Request $request,
        ForumTopicRepository $topicRepository,
        ForumCategoryRepository $categoryRepository,
        string $id
    ){
        $category = $categoryRepository->findOneBy(['id' => $id]);
        $catTopics = $topicRepository->findBy(['category' => $id]);

        $form = $this->createForm(ForumTopicType::class);
        $form->handleRequest($request);
        $now = new \DateTime();

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {
            /** @var ForumTopic $forumTopic */
            $forumTopic = $form->getData();
            $forumTopic->setCreatedAt($now);
            $forumTopic->setUpdatedAt($now);
            $forumTopic->setTopicCreator($currentUser);
            $forumTopic->setCategory($category);
            $em->persist($forumTopic);
            $em->flush();
            $this->addFlash('success', 'Forum Thema eröffnet!');
            return $this->redirectToRoute('forumCategoryView', ['id' => $id]);
        }

        return $this->render('forum/forumCategoryView.html.twig', [
            'title' => 'Forum Kategorie',
            'topics' => $catTopics,
            'category' => $category,
            'forumTopicForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/forum/topic/{id}", name="forumTopicView")
     */
    public function topicView(
        EntityManagerInterface $em,
        Request $request,
        ForumCategoryRepository $categoryRepository,
        ForumTopicRepository $topicRepository,
        ForumPostRepository $postRepository,
        ForumReplyRepository $replyRepository,
        string $id
    ){
        /** @var ForumTopic $forumTopic */
        $topic = $topicRepository->findOneBy(['id' => $id]);

        /** @var ForumCategory $category */
        $category = $categoryRepository->findOneBy(['id' => $topic->getCategory()->getId()]);

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $now = new \DateTime();
        $posts = $postRepository->findBy(['postTopic' => $id]);
        $replies = $replyRepository->findBy(['topic' => $id]);

        $form = $this->createForm(ForumPostType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var ForumPost $forumPost */
            $forumPost = $form->getData();
            $forumPost->setCreatedAt($now);
            $forumPost->setUpdatedAt($now);
            $forumPost->setPostCreator($currentUser);
            $forumPost->setPostTopic($topic);
            $forumPost->setPostCategory($category);
            $em->persist($forumPost);
            $em->flush();
            $this->addFlash('success', 'Forum Beitrag erstellt!');
            return $this->redirectToRoute('forumTopicView', ['id' => $id]);
        }

        return $this->render('forum/forumTopicView.html.twig', [
            'title' => 'Forum Thema',
            'category' => $category,
            'topic' => $topic,
            'posts' => $posts,
            'replies' => $replies,
            'catId' =>  $topic->getCategory()->getId(),
            'forumPostForm' => $form->createView()
        ]);
    }
}
