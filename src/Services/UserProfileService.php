<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserProfileService
{
    private const LOGIN_SCORE_MULTIPLIER = 10;
    private const TOPIC_SCORE_MULTIPLIER = 50;
    private const POST_SCORE_MULTIPLIER = 20;
    private const REPLY_SCORE_MULTIPLIER = 5;
    private const RECEIVE_KUDOS_SCORE_MULTIPLIER = 30;
    private const GIVEN_KUDOS_SCORE_MULTIPLIER = 10;

    /**
     * @param User $profileUser
     * @return int
     */
    public function getReceivedKudos(User $profileUser):int
    {
        $userPosts = $profileUser->getForumPosts();

        $gainedKudos = [];

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
        $userPostKudos = $profileUser->getPostKudos();

        $givenKudos = [];

        foreach($userPostKudos as $kudo){
            if($kudo->getPost()->getPostCreator() !== $profileUser){
                $givenKudos[] = $kudo;
            }
        }

        return count($givenKudos);
    }

    public function initScore(User $profileUser)
    {
        $topicScore = count($profileUser->getForumTopics()) * self::TOPIC_SCORE_MULTIPLIER;
        $postScore = count($profileUser->getForumPosts()) * self::POST_SCORE_MULTIPLIER;
        $receivedKudoScore = $this->getReceivedKudos($profileUser) * self::RECEIVE_KUDOS_SCORE_MULTIPLIER;
        $givenKudoScore = $this->getGivenKudos($profileUser) * self::GIVEN_KUDOS_SCORE_MULTIPLIER;

        $score = $topicScore + $postScore + $receivedKudoScore + $givenKudoScore;

        return $score;
    }

    public function daysSinceLastLogin(User $user)
    {
        $now = new \DateTime('now');
        $lastLogin = $user->getLastLogin();

        return abs($lastLogin->getTimestamp() - $now->getTimestamp())/60/60/24;

    }

    public function increaseScore(User $user, string $action):void
    {
        switch ($action){
            case 'login':
                $user->setScore($user->getScore() + self::LOGIN_SCORE_MULTIPLIER);
                break;
            case 'topic':
                $user->setScore($user->getScore() + self::TOPIC_SCORE_MULTIPLIER);
                break;
            case 'post':
                $user->setScore($user->getScore() + self::POST_SCORE_MULTIPLIER);
                break;
            case 'reply':
                $user->setScore($user->getScore() + self::REPLY_SCORE_MULTIPLIER);
                break;
            case 'receivedKudo':
                $user->setScore($user->getScore() + self::RECEIVE_KUDOS_SCORE_MULTIPLIER);
                break;
            case 'givenKudo':
                $user->setScore($user->getScore() + self::GIVEN_KUDOS_SCORE_MULTIPLIER);

        }
    }

    public function decreaseScore(User $user, string $action):void
    {
        switch ($action){
            case 'topic':
                $user->setScore($user->getScore() - self::TOPIC_SCORE_MULTIPLIER);
                break;
            case 'post':
                $user->setScore($user->getScore() - self::POST_SCORE_MULTIPLIER);
                break;
            case 'reply':
                $user->setScore($user->getScore() - self::REPLY_SCORE_MULTIPLIER);
                break;
            case 'receivedKudo':
                $user->setScore($user->getScore() - self::RECEIVE_KUDOS_SCORE_MULTIPLIER);
                break;
            case 'givenKudo':
                $user->setScore($user->getScore() - self::GIVEN_KUDOS_SCORE_MULTIPLIER);
        }
    }
}