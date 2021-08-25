<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(WishRepository $repo): Response
    {
        $tab = $repo->findAll();
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
        return $this->render('wish/detail.html.twig', [
            "id" => $id,
        ]);
    }

            /**
     * @Route("/ajouter", name="ajouter")
     */
    public function ajouter(EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $wish->setAuthor('Toto');
        $wish->setTitle('blagues');
        $wish->setDescription('Blagues à TOTO !!!');
        $wish->setIsPublished(1);

        $em->persist($wish);        // met wish à disposition
        $em->flush();                   //on sauvegarde en BDD

        //redirection vers la route list
        return $this->redirectToRoute('list');
    

    }
}
