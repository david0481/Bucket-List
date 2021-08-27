<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Wish;
use App\Form\WishFormType;
use App\Repository\CategorieRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/gestion-wishes", name="gestion_wishes")
     */
    public function GestionWishes(WishRepository $repo): Response
    {
        $tab = $repo->findBy([],['dateCreated' => 'desc']);

        return $this->render('admin/gestionWishes.html.twig', [
            'wishes' => $tab,
        ]);
    }

    /**
     * @Route("/admin/modifier-wish/{id}", name="modifier_wish")
     */
    public function modifier(Wish $wish, Request $request, EntityManagerInterface $em): Response
    {
         $formWish = $this ->createForm(WishFormType::class, $wish);
        $formWish->handleRequest($request);

        if($formWish->isSubmitted()) {
            $em->flush();
            return $this->redirectToRoute('accueil');
        } 

        return $this->render('admin/modifierWish.html.twig', [
             'formWish' => $formWish->createView(),
        ]);
    }

    /**
     * @Route("/admin/supprimer-wish/{id}", name="supprimer_wish")
     */
    public function supprimer(Wish $wish, Request $request, EntityManagerInterface $em): Response
    {

        $em->remove($wish);
        $em->flush();
        return $this->redirectToRoute('gestion_wishes');
    }

    /**
     * @Route("/admin/quick-add", name="quick_add")
     */
     public function quick_add(Request $req, EntityManagerInterface  $em, CategorieRepository $repoCateg): Response
    {
        $wish = new Wish();
        $categ = $repoCateg->find(4);
        $wish->setTitle($req->get('title'));
        $wish->setAuthor('Quicky');
        $wish->setCategorie($categ);  // problème !!
        $wish->setIsPublished(true);
        $wish->setDateCreated(new \DateTime());
        $em->persist($wish);
        $em->flush();
        return $this->redirectToRoute('gestion_wishes');
    }
}
