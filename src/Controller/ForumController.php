<?php

namespace App\Controller;

use App\Entity\ForumCategory;
use App\Entity\ForumTopic;
use App\Entity\User;
use App\Form\ForumCategoryType;
use App\Form\ForumTopicType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumTopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
            $this->addFlash('success', 'Forum Category added!');
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
            $this->addFlash('success', 'Forum Thema eröffnet!');
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
     * @Route("/forum/topic/{id}", name="forumTopicView")
     */
    public function topicView(
        EntityManagerInterface $em,
        Request $request,
        ForumTopicRepository $topicRepository,
        string $id
    ){
        /** @var ForumTopic $forumTopic */
        $topic = $topicRepository->findOneBy(['id' => $id]);

        return $this->render('forum/forumTopicView.html.twig', [
            'title' => 'Forum Thema',
            'topic' => $topic,
            'catId' =>  $topic->getCategory()->getId()
        ]);
    }
}
