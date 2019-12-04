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
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
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
    public function createCategory(EntityManagerInterface $em, Request $request, ForumCategoryRepository $repository)
    {
        $form = $this->createForm(ForumCategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var ForumCategory $forumCategory */
            $forumCategory = $form->getData();
            $forumCategory->setCreatedAt(new \DateTime());
            $forumCategory->setUpdatedAt(new \DateTime());
            $em->persist($forumCategory);
            $em->flush();
            $this->addFlash('success', 'Eine neue Kategorie wurde hinzugefügt!');
            return $this->redirectToRoute('forumView');
        }
    }

    /**
     * @Route("/forum/{catId}/createTopic", name="forumCreateTopic")
     */
    public function createTopic(EntityManagerInterface $em, ForumCategoryRepository $categoryRepository, Request $request, string $catId)
    {
        $form = $this->createForm(ForumTopicType::class);
        $form->handleRequest($request);

        /** @var ForumCategory $category */
        $category = $categoryRepository->findOneBy(['id' => $catId]);

        /** @var User $user */
        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {
            /** @var ForumTopic $forumTopic */
            $forumTopic = $form->getData();
            $forumTopic->setCreatedAt(new \DateTime());
            $forumTopic->setUpdatedAt(new \DateTime());
            $forumTopic->setTopicCreator($user);
            $forumTopic->setCategory($category);
            $em->persist($forumTopic);
            $em->flush();
            $this->addFlash('success', 'Ein neues Thema wurde eröffnet!');
            return $this->redirectToRoute('forumCategoryView', ['id' => $catId]);
        }
    }


    /**
     * @Route("/forum/{topicId}/{postId}/createReply", name="forumCreateReply")
     */
    public function createReply(
        EntityManagerInterface $em,
        Request $request,
        ForumPostRepository $postRepository,
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


        if($replyForm->isSubmitted() && $replyForm->isValid()) {

            /** @var ForumReply $postReply */
            $postReply = $replyForm->getData();
            $postReply->setCreatedAt(new \DateTime());
            $postReply->setUpdatedAt(new \DateTime());
            $postReply->setReplyCreator($currentUser);
            $postReply->setTopic($post->getPostTopic());
            $postReply->setPost($post);
            $em->persist($postReply);

            $topic->setUpdatedAt(new \DateTime());
            $em->persist($topic);

            $post->setUpdatedAt(new \DateTime());
            $em->persist($post);

            $category->setUpdatedAt(new \DateTime());
            $em->persist($category);

            $em->flush();
            $this->addFlash('success', 'Eine neue Antwort wurde hinzugefügt!');
            return $this->redirectToRoute('forumTopicView', ['topicId' => $post->getPostTopic()->getId()]);
        }
    }
}