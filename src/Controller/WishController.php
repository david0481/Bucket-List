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
     * @Route("/list", name="list")
     */
    public function list(WishRepository $repo): Response
    {
        $tab = $repo->findBy([],['dateCreated' => 'desc']);
        dump($tab);

        return $this->render('wish/list.html.twig', [
            'wishes' => $tab,
        ]);
    }

        /**
     * @Route("/list/detail/{id}", name="list_detail")
     */
    public function detail($id): Response
    {

        // on appelle wish
        $wish = $this->getDoctrine()->getRepository(Wish::class)->findBy(['id' => $id],[]);
        dump($wish);

        return $this->render('wish/detail.html.twig', [
            "id" => $id,
            "wish" => $wish,
        ]);
    }

            /**
     * @Route("/ajouter", name="ajouter")
     */
    public function ajouter(EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $wish->setAuthor('Avé César');
        $wish->setTitle('Romain');
        $wish->setDescription('Je suis un empereur !!!');
        $wish->setIsPublished(1);
        $wish->setDateCreated(new DateTime('now'));

        $em->persist($wish);        // met wish à disposition
        $em->flush();                   //on sauvegarde en BDD

        //redirection vers la route list
        return $this->redirectToRoute('list');
    }

        /**
     * @Route("/add", name="wish_add")
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
            return $this->redirectToRoute('list');
        }

        return $this->render('wish/add.html.twig', [
            'formWish' => $formWish->createView(),
        ]);
    }


}

