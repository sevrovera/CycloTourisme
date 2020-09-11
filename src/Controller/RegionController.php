<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegionController extends AbstractController
{
    /**
     * @Route("/region", name="region")
     */
    public function index()
    {
        return $this->render('region/index.html.twig', [
            'controller_name' => 'RegionController',
        ]);
    }
}
