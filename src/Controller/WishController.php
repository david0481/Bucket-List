<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishFormType;
use App\Repository\WishRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/user/list/detail/{id}", name="list_detail")
     */
    public function detail(Wish $wish): Response
    {

        // on appelle wish
       // $wish = $this->getDoctrine()->getRepository(Wish::class)->findBy(['id' => $id],[]);


        return $this->render('wish/detail.html.twig', [
            "wish" => $wish,
        ]);
    }

        /**
     * @Route("/user/add", name="wish_add")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        //je crée un objet formulaire
        $formWish = $this ->createForm(WishFormType::class, $wish);
        //je viens hydrater $wish
        $formWish->handleRequest($request);

        if($formWish->isSubmitted()) {
            $wish->setDateCreated(new DateTime('now'));
            $wish->setIsPublished(true);
            $em->persist($wish);
            $em->flush();
            return $this->redirectToRoute('accueil');
        }

        return $this->render('wish/add.html.twig', [
            'formWish' => $formWish->createView(),
        ]);
    }

    /**
     * @Route("/user/ajouter-brut", name="wish_ajouter_brut")
     */
    public function ajouterBrut(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $wish = $request->get('title');
        $wish->setTitle($wish);
        $em->persist($wish);
        $em->flush();
        return $this->redirectToRoute('accueil');
    }
}



