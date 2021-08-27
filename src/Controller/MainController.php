<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function home(WishRepository $repo): Response
    {
        $tab = $repo->findBy([],['dateCreated' => 'desc']);

        return $this->render('main/accueil.html.twig', [
            'wishes' => $tab,
        ]);
    }

     /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
    
        return $this->render('main/contact.html.twig', [
            
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
    
        return $this->render('main/about.html.twig', [
            
        ]);
    }
}
