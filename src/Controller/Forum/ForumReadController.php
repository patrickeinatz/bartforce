<?php

namespace App\Controller\Forum;

use App\Entity\ForumCategory;
use App\Entity\ForumTopic;
use App\Entity\User;
use App\Form\ForumCategoryType;
use App\Form\ForumPostType;
use App\Form\ForumReplyType;
use App\Form\ForumTopicType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use App\Repository\ForumReplyRepository;
use App\Repository\ForumTopicRepository;
use App\Repository\PostContentModuleRepository;
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
class ForumReadController extends AbstractController
{
    /**
     * @Route("/forum", name="forumView")
     */
    public function forumView(
        EntityManagerInterface $em,
        Request $request,
        ForumCategoryRepository $categoryRepository
    ){
        $categories = $categoryRepository->findAll();

        $form = $this->createForm(ForumCategoryType::class);
        $form->handleRequest($request);

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
        Request $request,
        ForumTopicRepository $topicRepository,
        ForumCategoryRepository $categoryRepository,
        PostContentModuleRepository $postContentModuleRepository,
        string $id
    ){
        $category = $categoryRepository->findOneBy(['id' => $id]);
        $catTopics = $topicRepository->findBy(['category' => $id]);
        $postModules = $postContentModuleRepository->findAll();

        $postModuleIcons = [];

        foreach ($postModules as $module){
            $postModuleIcons[$module->getTitle()] = $module->getIcon();
        }

        $categoryForm = $this->createForm(ForumCategoryType::class);
        $categoryForm->handleRequest($request);

        $topicForm = $this->createForm(ForumTopicType::class);
        $topicForm->handleRequest($request);

        $postForm = $this->createForm(ForumPostType::class);
        $postForm->handleRequest($request);

        return $this->render('forum/forumCategoryView.html.twig', [
            'title' => 'Forum Kategorie',
            'topics' => $catTopics,
            'category' => $category,
            'forumTopicForm' => $topicForm->createView(),
            'forumCategoryForm' => $categoryForm->createView(),
            'forumPostForm' => $postForm->createView(),
            'postModuleIcons' => $postModuleIcons
        ]);
    }

    /**
     * @Route("/forum/topic/{topicId}", name="forumTopicView")
     */
    public function topicView(
        EntityManagerInterface $em,
        Request $request,
        ForumCategoryRepository $categoryRepository,
        ForumTopicRepository $topicRepository,
        ForumPostRepository $postRepository,
        ForumReplyRepository $replyRepository,
        PostContentModuleRepository $postContentModuleRepository,
        string $topicId
    ){
        /** @var ForumTopic $forumTopic */
        $topic = $topicRepository->findOneBy(['id' => $topicId]);

        /** @var ForumCategory $category */
        $category = $categoryRepository->findOneBy(['id' => $topic->getCategory()->getId()]);

        /** @var User $currentUser */
        $posts = $postRepository->findBy(['postTopic' => $topicId]);
        $replies = $replyRepository->findBy(['topic' => $topicId]);

        $postModules = $postContentModuleRepository->findAll();
        $postModuleIcons = [];

        foreach ($postModules as $module){
            $postModuleIcons[$module->getTitle()] = $module->getIcon();
        }

        $topicForm = $this->createForm(ForumTopicType::class);
        $topicForm->handleRequest($request);

        $postForm = $this->createForm(ForumPostType::class);
        $postForm->handleRequest($request);

        $replyForm = $this->createForm(ForumReplyType::class);
        $replyForm->handleRequest($request);

        return $this->render('forum/forumTopicView.html.twig', [
            'title' => 'Forum Thema',
            'category' => $category,
            'topic' => $topic,
            'posts' => $posts,
            'replies' => $replies,
            'catId' =>  $topic->getCategory()->getId(),
            'forumTopicForm' => $topicForm->createView(),
            'forumPostForm' => $postForm->createView(),
            'forumReplyForm' => $replyForm->createView(),
            'postModuleIcons' => $postModuleIcons
        ]);
    }
}
