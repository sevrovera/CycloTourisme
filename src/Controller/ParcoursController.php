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

    public String $previousRoute;

    public function getPrevRoute(Request $request){
        $routeName = $request->attributes->get('_route');
        return $routeName;
    }

    /**
     * @Route("/parcours", name="parcours")
     */
    public function index(Request $request, ParcoursRepository $repo)
    {
        $parcoursList=$repo->findAll();
        $previousRoute=$request->attributes->get('_route');
        return $this->render('parcours/parcours.html.twig', [
            'parcoursList'=>$parcoursList,
            'previousRoute'=>$previousRoute,
        ]);
    }

    /**
     * @Route("/region/{id}", name="parcours_region")
     */
    public function parcoursRegion(Request $request, ParcoursRepository $repoP, RegionRepository $repoR, $id)
    {
        $region=$repoR->find($id);
        $parcoursList = $region->getParcours();
        $previousRoute=$request->attributes->get('_route');
        return $this->render('parcours/parcours.html.twig', [
            'parcoursList'=>$parcoursList,
            'previousRoute'=>$previousRoute,
            'region'=>$region->getName(),
        ]);
    }

    /**
     * @Route("parcours/create/admin", name="add_parcours", methods={"get", "post"})
     */
    public function createParcours(Request $request, EntityManagerInterface $em, RegionRepository $repo) : Response {

        if ($request->isMethod("POST")){
            $data=$request->request->all();
            $region=$repo->find($data['region']);
            $parcours = new Parcours();
            $parcours->setName($data['name']);
            $parcours->setRegion($region);
            if (!empty($_POST["description"])) {
                $parcours->setDescription($data['description']);    
            }
            if (!empty($_POST["duration"])) {
                $parcours->setDuration($data['duration']);    
            }
            if (!empty($_POST["difficulty"])) {
                $parcours->setDifficulty($data['difficulty']);    
            }
            if (!empty($_POST["cost"])) {
                $parcours->setCost($data['cost']);    
            }
            if (!empty($_POST["maxParticipants"])) {
                $parcours->setMaxPArticipants($data['maxParticipants']);    
            }
            if (!empty($_POST["registeredParticipants"])) {
                $parcours->setRegisteredParticipants($data['registeredParticipants']);    
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
