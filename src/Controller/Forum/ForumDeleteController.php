<?php


namespace App\Controller\Forum;

use App\Entity\ForumCategory;
use App\Entity\User;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use App\Repository\ForumReplyRepository;
use App\Repository\ForumTopicRepository;
use App\Services\DiscordService;
use App\Services\UserProfileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_USER for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 */
class ForumDeleteController extends AbstractController
{

    /**
     * @Route("/forum/deleteCategory/{categoryId}", name="forumDeleteCategory")
     */
    public function deleteCategory(
        EntityManagerInterface $em,
        ForumCategoryRepository $categoryRepository,
        ForumReplyRepository $replyRepository,
        DiscordService $discordService,
        string $categoryId)
    {
        /** @var ForumCategory $category */
        $category = $categoryRepository->findOneBy(['id' => $categoryId]);
        $topics = $category->getForumTopics();
        $posts = $category->getForumPosts();

        //Delete related discord channel
        $discordService->deleteChannel(
            $category->getRelatedDiscordChannelId()
        );

        $replyCount = 0;

        foreach ($topics as $topic){
            $replies = $replyRepository->findBy(['topic' => $topic]);
            $replyCount *= sizeof($replies);

            foreach ($replies as $reply){
                $em->remove($reply);
            }
            $em->remove($topic);
        }

        foreach ($posts as $post){
            $em->remove($post);
        }

        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'Eine Kategorie wurde gelöscht!');
        return $this->redirectToRoute('forumView');

    }

    /**
     * @Route("/forum/deleteTopic/{topicId}", name="forumDeleteTopic")
     */
    public function deleteTopic(
        EntityManagerInterface $em,
        ForumTopicRepository $topicRepository,
        ForumPostRepository $postRepository,
        ForumReplyRepository $replyRepository,
        DiscordService $discordService,
        UserProfileService $userProfileService,
        string $topicId
    )
    {
        $topic = $topicRepository->findOneBy(['id' => $topicId]);
        $topicPosts = $postRepository->findBy(['postTopic' => $topic]);
        $postReplies = $replyRepository->findBy(['topic' => $topic]);

        $categoryId = $topic->getCategory()->getId();

        $postCount = sizeof($topicPosts);
        $replyCount = sizeof($postReplies);

        $discordService->sendChannelMsg(
            $topic->getCategory()->getRelatedDiscordChannelId(),
            ' **'.$this->getUser()->getUsername().'** hat das Thema: **"'.$topic->getTitle().'"** entgültig geschlossen!'
        );

        $userProfileService->decreaseScore($topic->getTopicCreator(), 'topic');

        $em->remove($topic);

        foreach ($topicPosts as $post){
            $userProfileService->decreaseScore($post->getPostCreator(), 'post');
            $em->remove($post);
        }

        foreach($postReplies as $reply){
            $userProfileService->decreaseScore($reply->getReplyCreator(), 'reply');
            $em->remove($reply);
        }

        $em->flush();
        $this->addFlash('success', 'Ein Thema mit '.$postCount.' Beiträgen und '.$replyCount.' Antworten wurde gelöscht!');
        return $this->redirectToRoute('forumCategoryView', ['id' => $categoryId]);
    }

    /**
     * @Route("/forum/deletePost/{postId}", name="forumDeletePost")
     */
    public function deletePost(
        EntityManagerInterface $em,
        ForumPostRepository $postRepository,
        ForumReplyRepository $replyRepository,
        DiscordService $discordService,
        UserProfileService $userProfileService,
        string $postId
    )
    {
        $post = $postRepository->findOneBy(['id' => $postId]);
        $postReplies = $replyRepository->findBy(['post' => $post]);

        $replyCount = sizeof($postReplies);
        $topicId = $post->getPostTopic()->getId();

        $discordService->sendChannelMsg(
            $post->getPostTopic()->getCategory()->getRelatedDiscordChannelId(),
            'Ein Beitrag von **'.$post->getPostCreator()->getUsername().'** wurde gewaltsam aus dem Thema: **"'.$post->getPostTopic()->getTitle().'"** entfernt! Denk mal drüber nach.');

        $userProfileService->decreaseScore($post->getPostCreator(), 'post');
        $em->remove($post);
        foreach($postReplies as $reply){
            $userProfileService->decreaseScore($reply->getReplyCreator(), 'reply');
            $em->remove($reply);
        }

        $em->flush();
        $this->addFlash('success', 'Ein Beitrag mit '.$replyCount.' Antworten wurde gelöscht!');
        return $this->redirectToRoute('forumTopicView', ['topicId' => $topicId]);
    }

    /**
     * @Route("/forum/deleteReply/{replyId}", name="forumDeleteReply")
     */
    public function deleteReply(
        EntityManagerInterface $em,
        ForumPostRepository $postRepository,
        ForumReplyRepository $replyRepository,
        UserProfileService $userProfileService,
        string $replyId)
    {
        $reply = $replyRepository->findOneBy(['id' => $replyId]);
        $topicId = $reply->getTopic()->getId();

        $userProfileService->decreaseScore($reply->getReplyCreator(), 'reply');

        $em->remove($reply);
        $em->flush();
        $this->addFlash('success', 'Eine Antwort wurde gelöscht!');
        return $this->redirectToRoute('forumTopicView', ['topicId' => $topicId]);
    }
}