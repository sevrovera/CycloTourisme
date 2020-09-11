<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Parcours;
use App\Repository\ParcoursRepository;


class ParcoursController extends AbstractController
{
    /**
     * @Route("/", name="parcours")
     */
    public function index(ParcoursRepository $repo)
    {
        $parcoursList=$repo->findAll();
        return $this->render('parcours/parcours.html.twig', [
            'parcoursList'=>$parcoursList
        ]);
    }

    /**
     * @Route("parcours/create", name="app_create_parcours", methods={"get", "post"})
     */
    public function createParcours(Request $request, EntityManagerInterface $em) : Response {

        if ($request->isMethod("POST")){
            $data=$request->request->all();
            $parcours = new Parcours();
            $parcours->setName($data['name']);
            $parcours->setDuration($data['duration']);
            // $parcours->setRegion($data['region']);

            $em->persist($parcours);
            $em->flush();

            return $this->redirectToRoute('parcours');
        }

        return $this->render("parcours/newParcours.html.twig");

    }
}
