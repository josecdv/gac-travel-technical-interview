<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
   /**
    * @Route("/default")
    */
public function number(): Response
    {
        //return $this->render('public/index.html.twig', [ ]);
        return new Response ('<html><body> it is work </body></html>');

    }
}