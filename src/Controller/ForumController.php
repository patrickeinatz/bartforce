<?php

namespace App\Controller;

use App\Entity\ForumCategory;
use App\Form\ForumCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forumView")
     */
    public function forumView(EntityManagerInterface $em, Request $request)
    {
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
            'forumCategoryForm' => $form->createView()
        ]);
    }

}
