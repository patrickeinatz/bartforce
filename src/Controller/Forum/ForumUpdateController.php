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
class ForumUpdateController extends AbstractController
{
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