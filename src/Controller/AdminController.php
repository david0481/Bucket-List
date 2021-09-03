<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\User;
use App\Entity\Wish;
use App\Form\EditUserType;
use App\Form\WishFormType;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // quel est l id du user connecter ?
        $user = $this->getUser();
                
        return $this->render('admin/gestion.html.twig', [
            'user' => $user,
        ]);
    }

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

        if($formWish->isSubmitted() && $formWish->isValid()) {
            $em->flush();

            $this->addFlash(
                'success',
                'Changement effectué'
            );
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

    /**
     * @Route("/admin/utilisateurs", name="admin_utilisateurs")
     */
    public function usersList(UserRepository $users)
    {
        return $this->render('admin/users.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    /**
     * @Route("/admin/utilisateurs/modifier/{id}", name="admin_modifier_utilisateur")
     */
    public function editUser(User $user, Request $request)
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_utilisateurs');
        }
        
        return $this->render('admin/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/utilisateurs/supprimer/{id}", name="admin_supprimer_utilisateur")
     */
    public function supprimerUser(User $user, EntityManagerInterface $em): Response
    {

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès');

        return $this->redirectToRoute('admin_utilisateurs');
    }

}
