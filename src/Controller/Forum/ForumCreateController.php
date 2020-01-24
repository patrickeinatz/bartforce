<?php

namespace App\Controller\Forum;

use App\Entity\ForumCategory;
use App\Entity\ForumPost;
use App\Entity\ForumReply;
use App\Entity\ForumTopic;
use App\Entity\TopicContentModule;
use App\Entity\User;
use App\Form\ForumCategoryType;
use App\Form\ForumPostType;
use App\Form\ForumReplyType;
use App\Form\ForumTopicType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use App\Repository\ForumTopicRepository;
use App\Services\DiscordService;
use App\Services\ForumService;
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
class ForumCreateController extends AbstractController
{

    /**
     * @Route("/forum/createCategory", name="forumCreateCategory")
     */
    public function createCategory(EntityManagerInterface $em, Request $request, ForumCategoryRepository $repository, DiscordService $discordService)
    {
        $form = $this->createForm(ForumCategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var ForumCategory $forumCategory */
            $forumCategory = $form->getData();

            //create new discord text channel
            $forumCategory->setRelatedDiscordChannelId(
                $discordService->createNewTextChannel($forumCategory->getTitle())
            );


            $now = new \DateTime('now');

            $forumCategory->setCreatedAt($now);
            $forumCategory->setUpdatedAt($now);
            $em->persist($forumCategory);
            $em->flush();
            $this->addFlash('success', 'Eine neue Kategorie wurde hinzugefügt!');

            $discordService->sendChannelMsg(
                $forumCategory->getRelatedDiscordChannelId(),
                'Eine neue Kategorie mit dem Titel "'.$forumCategory->getTitle().'" wurde im Forum eröffnet! 
https://www.bartforce.de/forum/category/'.$forumCategory->getId()
            );

            return $this->redirectToRoute('forumView');
        }
    }

    /**
     * @Route("/forum/{catId}/createTopic", name="forumCreateTopic")
     */
    public function createTopic(EntityManagerInterface $em, ForumCategoryRepository $categoryRepository, ForumService $forumService, Request $request, DiscordService $discordService, string $catId)
    {
        $form = $this->createForm(ForumTopicType::class);
        $form->handleRequest($request);

        /** @var ForumCategory $category */
        $category = $categoryRepository->findOneBy(['id' => $catId]);

        /** @var User $user */
        $user = $this->getUser();

        $now = new \DateTime('now');

        if($form->isSubmitted() && $form->isValid()) {
            /** @var ForumTopic $forumTopic */
            $forumTopic = $form->getData();

            if($forumTopic->getTopicContentModule()->getTitle() === 'image'){
                $forumTopic->setTopicContent(
                    $forumService->makeImageLink($forumTopic->getTopicContent())
                );
            }

            if($forumTopic->getTopicContentModule()->getTitle() === 'video'){
                $forumTopic->setTopicContent(
                    $forumService->makeYouTubeEmbedLink($forumTopic->getTopicContent())
                );

            }

            $forumTopic->setCreatedAt($now);
            $forumTopic->setUpdatedAt($now);
            $forumTopic->setTopicCreator($user);
            $forumTopic->setCategory($category);

            $category->setUpdatedAt($now);
            $em->persist($category);
            $em->persist($forumTopic);
            $em->flush();

            $discordService->sendChannelMsg('670283443468238888','**'.$user->getUsername().'** hat das Thema ***'.$forumTopic->getTitle().'*** eröffnet! 
http://www.bartforce.de/forum/topic/'.$forumTopic->getId());

            $this->addFlash('success', 'Ein neues Thema wurde eröffnet!');
            return $this->redirectToRoute('forumCategoryView', ['id' => $catId]);
        }
    }


    /**
     * @Route("/forum/{catId}/{topicId}/createPost", name="forumCreatePost")
     */
    public function createPost(EntityManagerInterface $em, ForumPostRepository $postRepository, ForumTopicRepository $topicRepository, Request $request, string $topicId, DiscordService $discordService, string $catId)
    {
        $topic = $topicRepository->findOneBy(['id' =>  $topicId]);
        $category = $topic->getCategory();

        $postForm = $this->createForm(ForumPostType::class);
        $postForm->handleRequest($request);

        $now = new \DateTime('now');

        if($postForm->isSubmitted() && $postForm->isValid()) {
            /** @var ForumPost $forumPost */
            $forumPost = $postForm->getData();

            $forumPost->setCreatedAt($now);
            $forumPost->setUpdatedAt($now);
            $forumPost->setPostCreator($this->getUser());
            $forumPost->setPostTopic($topic);
            $forumPost->setPostCategory($category);

            $topic->setUpdatedAt($now);
            $category->setUpdatedAt($now);

            $em->persist($topic);
            $em->persist($category);
            $em->persist($forumPost);
            $em->flush();

            $discordService->sendChannelMsg($category->getRelatedDiscordChannelId(),'**'.$this->getUser()->getUsername().'** hat seinen Senf zum Thema **"'.$topic->getTitle().'"** dazugegeben! 
http://www.bartforce.de/forum/topic/'.$topic->getId());

            $this->addFlash('success', 'Ein neuer Beitrag wurde erstellt!');

            return $this->redirectToRoute('forumTopicView', ['topicId' => $topicId]);
        }
    }

    /**
     * @Route("/forum/{topicId}/{postId}/createReply", name="forumCreateReply")
     */
    public function createReply(
        EntityManagerInterface $em,
        Request $request,
        ForumPostRepository $postRepository,
        string $topicId,
        string $postId
    ){

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        /** @var ForumPost $post */
        $post = $postRepository->findOneBy(['id' => $postId]);
        $topic = $post->getPostTopic();
        $category = $post->getPostCategory();

        $replyForm = $this->createForm(ForumReplyType::class);
        $replyForm->handleRequest($request);

        $now = $now = new \DateTime('now');

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
}