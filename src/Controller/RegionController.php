<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Region;
use App\Repository\RegionRepository;


class RegionController extends AbstractController
{
    /**
     * @Route("/regions", name="regions")
     */
    public function index(RegionRepository $repo)
    {
        $regions=$repo->findAll();
        return $this->render('region/region.html.twig', ['regions'=>$regions]);

    }

    /**
     * @Route("/create/region/admin", name="add_region", methods={"get", "post"})
     */
    public function createRegion(Request $request, EntityManagerInterface $em) : Response {

        if ($request->isMethod("POST")){
            $data=$request->request->all();
            $region = new Region();
            $region->setName($data['name']);

            // insertion de la nouvelle région en db avec Doctrine
            $em->persist($region);
            // équivalent d'un commit (peut se faire après plusieurs actions de persistance)
            $em->flush();

            return $this->redirectToRoute('regions');
        }

        return $this->render("region/newRegion.html.twig");
    }

}
