<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BuildsController extends AbstractController
{
    #[Route('/builds', name: 'builds')]
    public function index(): Response
    {
        return $this->render('builds/index.html.twig');
    }
}