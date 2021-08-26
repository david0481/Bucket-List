<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishFormType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/gestion-wishes", name="gestion_wishes")
     */
    public function GestionWishes(WishRepository $repo): Response
    {
        $tab = $repo->findBy([],['dateCreated' => 'desc']);

        return $this->render('admin/gestionWishes.html.twig', [
            'wishes' => $tab,
        ]);
    }

    /**
     * @Route("/modifier-wish/{id}", name="modifier_wish")
     */
    public function modifier(Wish $wish, Request $request, EntityManagerInterface $em): Response
    {
         $formWish = $this ->createForm(WishFormType::class, $wish);
        $formWish->handleRequest($request);

        if($formWish->isSubmitted()) {
            $em->flush();
            return $this->redirectToRoute('list');
        } 

        return $this->render('admin/modifierWish.html.twig', [
             'formWish' => $formWish->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-wish/{id}", name="supprimer_wish")
     */
    public function supprimer(Wish $wish, Request $request, EntityManagerInterface $em): Response
    {
        $formWish = $this ->createForm(WishFormType::class, $wish);
        $formWish->handleRequest($request);

        if($formWish->isSubmitted()) {
            $em->remove($wish);
            $em->flush();
            return $this->redirectToRoute('list');
        } 

        return $this->render('admin/supprimerWish.html.twig', [
             'formWish' => $formWish->createView(),
        ]);
    }
}
