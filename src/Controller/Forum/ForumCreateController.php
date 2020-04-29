<?php

namespace App\Controller\Forum;

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
use App\Repository\ForumTopicRepository;
use App\Repository\PostContentModuleRepository;
use App\Services\DiscordService;
use App\Services\ForumService;
use App\Services\UserProfileService;
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
                'Eine neue Kategorie mit dem Titel "'.$forumCategory->getTitle().'" wurde im Forum eröffnet!'
            );

            return $this->redirectToRoute('forumView');
        }
    }

    /**
     * @Route("/forum/{catId}/createTopic", name="forumCreateTopic")
     */
    public function createTopic(
        EntityManagerInterface $em,
        ForumCategoryRepository $categoryRepository,
        PostContentModuleRepository $postContentModuleRepository,
        Request $request,
        DiscordService $discordService,
        UserProfileService $userProfileService,
        ForumService $forumService,
        string $catId
    )
    {
        $data =$request->get('forum_post');

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

            $forumTopic->setCreatedAt($now);
            $forumTopic->setUpdatedAt($now);
            $forumTopic->setTopicCreator($user);
            $forumTopic->setCategory($category);

            $category->setUpdatedAt($now);

            /** @var ForumPost $forumTopicPost */
            $forumTopicPost = new ForumPost();

            $forumTopicPost->setPostTopic($forumTopic);
            $forumTopicPost->setPostCategory($category);
            $forumTopicPost->setPostCreator($user);
            $forumTopicPost->setCreatedAt($now);
            $forumTopicPost->setUpdatedAt($now);
            $forumTopicPost->setPostText($data['postText']);
            $forumTopicPost->setPostContentModule($postContentModuleRepository->findOneBy(['id' => $data['postContentModule']]));

            if($forumTopicPost->getPostContentModule()->getTitle() === 'image'){
                $forumTopicPost->setPostContent(
                    $forumService->makeImageLink($data['postContent'])
                );
            }

            if($forumTopicPost->getPostContentModule()->getTitle() === 'video'){
                $forumTopicPost->setPostContent(
                    $forumService->makeYouTubeEmbedLink($data['postContent'])
                );
            }

            $userProfileService->increaseScore($user, 'topic');
            $userProfileService->increaseScore($user, 'post');

            $em->persist($category);
            $em->persist($forumTopic);
            $em->persist($forumTopicPost);
            $em->flush();

            $discordService->sendChannelMsg(
                $forumTopic->getCategory()->getRelatedDiscordChannelId(),
                '**'.$user->getUsername().'** hat das Thema ***'.$forumTopic->getTitle().'*** eröffnet! '.$data['postContent']." ".$forumTopicPost->getPostText()
            );

            $this->addFlash('success', 'Ein neues Thema wurde eröffnet!');
            return $this->redirectToRoute('forumCategoryView', ['id' => $catId]);
        }
    }

    /**
     * @Route("/forum/{catId}/{topicId}/createPost", name="forumCreatePost")
     */
    public function createPost(
        EntityManagerInterface $em,
        ForumService $forumService,
        ForumTopicRepository $topicRepository,
        Request $request,
        string $topicId,
        UserProfileService $userProfileService,
        DiscordService $discordService)
    {
        $topic = $topicRepository->findOneBy(['id' =>  $topicId]);
        $category = $topic->getCategory();

        $postForm = $this->createForm(ForumPostType::class);
        $postForm->handleRequest($request);

        $now = new \DateTime('now');

        /** @var User $user */
        $user = $this->getUser();

        if($postForm->isSubmitted() && $postForm->isValid()) {
            /** @var ForumPost $forumPost */
            $forumPost = $postForm->getData();

            $rawLink = $forumPost->getPostContent();

            if($forumPost->getPostContentModule()->getTitle() === 'image'){
                $forumPost->setPostContent(
                    $forumService->makeImageLink($forumPost->getPostContent())
                );
            }

            if($forumPost->getPostContentModule()->getTitle() === 'video'){
                $forumPost->setPostContent(
                    $forumService->makeYouTubeEmbedLink($forumPost->getPostContent())
                );
            }

            $forumPost->setCreatedAt($now);
            $forumPost->setUpdatedAt($now);
            $forumPost->setPostCreator($user);
            $forumPost->setPostTopic($topic);
            $forumPost->setPostCategory($category);

            $topic->setUpdatedAt($now);
            $category->setUpdatedAt($now);

            $userProfileService->increaseScore($user, 'post');

            $em->persist($topic);
            $em->persist($category);
            $em->persist($forumPost);
            $em->flush();

            $discordService->sendChannelMsg(
                $category->getRelatedDiscordChannelId(),
                '**'.$this->getUser()->getUsername().'** hat seinen Senf zum Thema **"'.$topic->getTitle().'"** dazugegeben!'.$rawLink." ".$forumPost->getPostText()
            );

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
        UserProfileService $userProfileService,
        string $topicId,
        string $postId
    ){

        /** @var User $user */
        $user = $this->getUser();

        /** @var ForumPost $post */
        $post = $postRepository->findOneBy(['id' => $postId]);
        $topic = $post->getPostTopic();
        $category = $post->getPostCategory();

        $replyForm = $this->createForm(ForumReplyType::class);
        $replyForm->handleRequest($request);

        $now = new \DateTime('now');

        if($replyForm->isSubmitted() && $replyForm->isValid()) {

            /** @var ForumReply $postReply */
            $postReply = $replyForm->getData();
            $postReply->setCreatedAt($now);
            $postReply->setUpdatedAt($now);
            $postReply->setReplyCreator($user);
            $postReply->setTopic($post->getPostTopic());
            $postReply->setPost($post);

            $topic->setUpdatedAt($now);
            $post->setUpdatedAt($now);
            $category->setUpdatedAt($now);

            $userProfileService->increaseScore($user, 'reply');

            $em->persist($postReply);
            $em->persist($category);
            $em->persist($topic);
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Eine neue Antwort wurde hinzugefügt!');
            return $this->redirectToRoute('forumTopicView', ['topicId' => $post->getPostTopic()->getId()]);
        }
    }
}