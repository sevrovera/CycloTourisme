<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Region;
use App\Form\RegionType;
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
    public function createRegion(Request $request) : Response {

        //$data=$request->request->all();
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            // Updates new entity with data from form
            $region = $form->getData();

            // Saves new entity in db
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($region);
            $entityManager->flush();

            // Redirects to list of regions
            return $this->redirectToRoute('regions');
        }

        return $this->render('region/newRegion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/region/{id]/admin", name="update_region", methods={"get", "post", "put"})
     */
    public function updateRegion(Region $region, Request $request, RegionRepository $repoR, $id){

        $region =$repoR->find($id);
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            // Updates new entity with data from form
            $region = $form->getData();

            // Saves new entity in db
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($region);
            $entityManager->flush();

            // Redirects to list of regions
            return $this->redirectToRoute('regions');
        }

        return $this->render('region/newRegion.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
