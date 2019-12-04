<?php

namespace App\Controller;

use App\Entity\ForumCategory;
use App\Entity\ForumPost;
use App\Entity\ForumReply;
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
            $this->addFlash('success', 'Eine neue Kategorie wurde hinzugefügt!');
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
            $this->addFlash('success', 'Ein neues Thema wurde eröffnet!');
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
     * @Route("/forum/topic/{topicId}", name="forumTopicView")
     */
    public function topicView(
        EntityManagerInterface $em,
        Request $request,
        ForumCategoryRepository $categoryRepository,
        ForumTopicRepository $topicRepository,
        ForumPostRepository $postRepository,
        ForumReplyRepository $replyRepository,
        string $topicId
    ){
        /** @var ForumTopic $forumTopic */
        $topic = $topicRepository->findOneBy(['id' => $topicId]);

        /** @var ForumCategory $category */
        $category = $categoryRepository->findOneBy(['id' => $topic->getCategory()->getId()]);

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $now = new \DateTime();
        $posts = $postRepository->findBy(['postTopic' => $topicId]);
        $replies = $replyRepository->findBy(['topic' => $topicId]);

        $topicForm = $this->createForm(ForumTopicType::class);
        $topicForm->handleRequest($request);

        $postForm = $this->createForm(ForumPostType::class);
        $postForm->handleRequest($request);

        $replyForm = $this->createForm(ForumReplyType::class);
        $replyForm->handleRequest($request);

        if($postForm->isSubmitted() && $postForm->isValid()) {
            /** @var ForumPost $forumPost */
            $forumPost = $postForm->getData();
            $forumPost->setCreatedAt($now);
            $forumPost->setUpdatedAt($now);
            $forumPost->setPostCreator($currentUser);
            $forumPost->setPostTopic($topic);
            $forumPost->setPostCategory($category);

            $topic->setUpdatedAt($now);
            $category->setUpdatedAt($now);

            $em->persist($topic);
            $em->persist($category);
            $em->persist($forumPost);
            $em->flush();
            $this->addFlash('success', 'Ein neuer Beitrag wurde erstellt!');

            return $this->redirectToRoute('forumTopicView', ['topicId' => $topicId]);
        }

        return $this->render('forum/forumTopicView.html.twig', [
            'title' => 'Forum Thema',
            'category' => $category,
            'topic' => $topic,
            'posts' => $posts,
            'replies' => $replies,
            'catId' =>  $topic->getCategory()->getId(),
            'forumTopicForm' => $topicForm->createView(),
            'forumPostForm' => $postForm->createView(),
            'forumReplyForm' => $replyForm->createView()
        ]);
    }

    /**
     * @Route("/forum/postReply/{postId}", name="forumPostReply")
     */
    public function postReply(
        EntityManagerInterface $em,
        Request $request,
        ForumPostRepository $postRepository,
        string $postId
    ){
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $now = new \DateTime();

        /** @var ForumPost $posts */
        $post = $postRepository->findOneBy(['id' => $postId]);
        $topic = $post->getPostTopic();
        $category = $post->getPostCategory();

        $replyForm = $this->createForm(ForumReplyType::class);
        $replyForm->handleRequest($request);


        if($replyForm->isSubmitted() && $replyForm->isValid()) {

            /** @var ForumReply $postReply */
            $postReply = $replyForm->getData();
            $postReply->setCreatedAt($now);
            $postReply->setUpdatedAt($now);
            $postReply->setReplyCreator($currentUser);
            $postReply->setTopic($post->getPostTopic());
            $postReply->setPost($post);
            $em->persist($postReply);

            $topic->setUpdatedAt($now);
            $em->persist($topic);

            $post->setUpdatedAt($now);
            $em->persist($post);

            $category->setUpdatedAt($now);
            $em->persist($category);

            $em->flush();
            $this->addFlash('success', 'Eine neue Antwort wurde hinzugefügt!');
            return $this->redirectToRoute('forumTopicView', ['topicId' => $post->getPostTopic()->getId()]);
        }
    }

    /**
     * @Route("/forum/deleteTopic/{topicId}", name="forumDeleteTopic")
     */
    public function deleteTopic(EntityManagerInterface $em, ForumTopicRepository $topicRepository, ForumPostRepository $postRepository, ForumReplyRepository $replyRepository, string $topicId)
    {
        $topic = $topicRepository->findOneBy(['id' => $topicId]);
        $topicPosts = $postRepository->findBy(['postTopic' => $topic]);
        $postReplies = $replyRepository->findBy(['topic' => $topic]);

        $categoryId = $topic->getCategory()->getId();

        $postCount = sizeof($topicPosts);
        $replyCount = sizeof($postReplies);

        $em->remove($topic);

        foreach ($topicPosts as $post){
            $em->remove($post);
        }

        foreach($postReplies as $reply){
            $em->remove($reply);
        }

        $em->flush();
        $this->addFlash('success', 'Ein Thema mit '.$postCount.' Beiträgen und '.$replyCount.' Antworten wurde gelöscht!');
        return $this->redirectToRoute('forumCategoryView', ['id' => $categoryId]);
    }

    /**
     * @Route("/forum/deletePost/{postId}", name="forumDeletePost")
     */
    public function deletePost(EntityManagerInterface $em, ForumPostRepository $postRepository, ForumReplyRepository $replyRepository, string $postId)
    {
       $post = $postRepository->findOneBy(['id' => $postId]);
       $postReplies = $replyRepository->findBy(['post' => $post]);

       $replyCount = sizeof($postReplies);
       $topicId = $post->getPostTopic()->getId();

       $em->remove($post);
       foreach($postReplies as $reply){
           $em->remove($reply);
       }

       $em->flush();
        $this->addFlash('success', 'Ein Beitrag mit '.$replyCount.' Antworten wurde gelöscht!');
        return $this->redirectToRoute('forumTopicView', ['topicId' => $topicId]);
    }

    /**
     * @Route("/forum/deleteReply/{replyId}", name="forumDeleteReply")
     */
    public function deleteReply(EntityManagerInterface $em, ForumPostRepository $postRepository, ForumReplyRepository $replyRepository, string $replyId)
    {
        $reply = $replyRepository->findOneBy(['id' => $replyId]);
        $topicId = $reply->getTopic()->getId();

        $em->remove($reply);
        $em->flush();
        $this->addFlash('success', 'Eine Antwort wurde gelöscht!');
        return $this->redirectToRoute('forumTopicView', ['topicId' => $topicId]);
    }

    /**
     * @Route("/forum/topicUpdate/{topicId}", name="forumUpdatetopic")
     */
    public function updateTopic(
        EntityManagerInterface $em,
        Request $request,
        ForumTopicRepository $topicRepository,
        string $topicId
    ){
        $topic = $topicRepository->findOneBy(['id' => $topicId]);
        $category = $topic->getCategory();
        $now = new \DateTime();
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
        $now = new \DateTime();
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
