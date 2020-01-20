<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\User;

class UserProfileService
{
    /**
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
}