<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Parcours;
use App\Entity\Region;
use App\Repository\ParcoursRepository;
use App\Repository\RegionRepository;



class ParcoursController extends AbstractController
{
    /**
     * @Route("/parcours", name="parcours")
     */
    public function index(ParcoursRepository $repo)
    {
        $parcoursList=$repo->findAll();
        return $this->render('parcours/parcours.html.twig', [
            'parcoursList'=>$parcoursList
        ]);
    }

    /**
     * @Route("parcours/create/admin", name="app_create_parcours", methods={"get", "post"})
     */
    public function createParcours(Request $request, EntityManagerInterface $em, RegionRepository $repo) : Response {

        if ($request->isMethod("POST")){
            $data=$request->request->all();
            $region=$repo->find($data['region']);
            $parcours = new Parcours();
            $parcours->setName($data['name']);
            $parcours->setDuration($data['duration']);
            $parcours->setRegion($region);
            if (!empty($_POST["description"])) {
                $parcours->setDescription($data['description']);    
            }
            if (!empty($_POST["cost"])) {
                $parcours->setCost($data['cost']);    
            }
            $em->persist($parcours);
            $em->flush();

            return $this->redirectToRoute('parcours');
        }

        $regions=$repo->findAll();
        return $this->render("parcours/newParcours.html.twig", [
            'regions'=>$regions
        ]);

    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->redirectToRoute('parcours');
    }
}
