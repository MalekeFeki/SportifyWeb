<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class homeController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}
