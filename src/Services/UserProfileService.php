<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\User;

class UserProfileService
{
    private const TOPIC_SCORE_MULTIPLIER = 50;
    private const POST_SCORE_MULTIPLIER = 20;
    private const RECEIVE_KUDOS_SCORE_MULTIPLIER = 30;
    private const GIVEN_KUDOS_SCORE_MULTIPLIER = 10;

    /**
     * @param User $profileUser
     * @return int
     */
    public function getReceivedKudos(User $profileUser):int
    {
        $userTopics = $profileUser->getForumTopics();
        $userPosts = $profileUser->getForumPosts();

        $gainedKudos = [];

        foreach($userTopics as $topic){
            foreach($topic->getTopicKudos() as $kudo){
                if($kudo->getUser() !== $profileUser){
                    $gainedKudos[] = $kudo;
                }
            }
        }

        foreach($userPosts as $post){
            foreach($post->getPostKudos() as $kudo){
                if($kudo->getUser() !== $profileUser){
                    $gainedKudos[] = $kudo;
                }
            }
        }

        return count($gainedKudos);
    }

    /**
     * @param User $profileUser
     * @return int
     */
    public function getGivenKudos(User $profileUser)
    {
        $userTopicKudos = $profileUser->getTopicKudos();
        $userPostKudos = $profileUser->getPostKudos();

        $givenKudos = [];

        foreach($userTopicKudos as $kudo){
            if($kudo->getTopic()->getTopicCreator() !== $profileUser){
                $givenKudos[] = $kudo;
            }
        }

        foreach($userPostKudos as $kudo){
            if($kudo->getPost()->getPostCreator() !== $profileUser){
                $givenKudos[] = $kudo;
            }
        }

        return count($givenKudos);
    }

    public function calcScore(DiscordService $discordService, User $profileUser)
    {
        $topicScore = count($profileUser->getForumTopics()) * self::TOPIC_SCORE_MULTIPLIER;
        $postScore = count($profileUser->getForumPosts()) * self::POST_SCORE_MULTIPLIER;
        $receivedKudoScore = $this->getReceivedKudos($profileUser) * self::RECEIVE_KUDOS_SCORE_MULTIPLIER;
        $givenKudoScore = $this->getGivenKudos($profileUser) * self::GIVEN_KUDOS_SCORE_MULTIPLIER;

        $score = $topicScore + $postScore + $receivedKudoScore + $givenKudoScore;

        return $score;
    }
}