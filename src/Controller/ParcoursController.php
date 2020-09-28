<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Form;
use Symfony\Component\String\Slugger\SluggerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Parcours;
use App\Entity\Region;
use App\Form\ParcoursType;
use App\Repository\ParcoursRepository;
use App\Repository\RegionRepository;

use App\Service\FileUploader;


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
     * @Route("/parcours/{id}", name="detail_parcours")
     */
    public function showDetails(Request $request, ParcoursRepository $repoP, $id)
    {
        $parcours=$repoP->find($id);
        $coverPicturePath= 'photobank/' . $parcours->getCoverPicture();
        return $this->render('parcours/detailParcours.html.twig', [
            'parcours'=>$parcours,
            'coverPicturePath'=>$coverPicturePath,
        ]);
    }

    /**
     * @Route("parcours/create/admin", name="add_parcours", methods={"get", "post"})
     */
    public function createParcours(Request $request, FileUploader $fileUploader){

        $parcours = new Parcours();
        $form = $this->createForm(ParcoursType::class, $parcours);
        $form->handleRequest($request);

        try {
            // $this->getDataFromForm($form, $parcours, $request, $fileUploader);

            if ($form->isSubmitted() && $form->isValid()) {

                /** @var UploadedFile $coverPicture */
                $coverPicture = $form->get('coverPicture')->getData();
    
                $coverPictureFile = $form->get('coverPicture')->getData();
                if ($coverPictureFile) {
                    $coverPictureFileName = $fileUploader->upload($coverPictureFile);
                    $parcours->setCoverPicture($coverPictureFileName);
                }
            
                // Updates entity with data from form
                $parcours = $form->getData();
    
                // Saves entity in db
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($parcours);
                $entityManager->flush();
        
                // Redirects to list of biketours
                return $this->redirectToRoute('parcours');
            }

        } catch(\Exception $e) {
            error_log($e->getMessage());
        }

        return $this->render('parcours/newParcours.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("parcours/update/{id}/admin", name="update_parcours", methods={"get", "post", "put"})
     */
    public function updateParcours(Parcours $parcours, Request $request, FileUploader $fileUploader, ParcoursRepository $repoP, $id){

        // pre-populates the form with the data from the biketour
        $parcours=$repoP->find($id);
        $form = $this->createForm(ParcoursType::class, $parcours);
        $form->handleRequest($request);
        
        try {
            // $this->getDataFromForm($form, $parcours, $request, $fileUploader);

            if ($form->isSubmitted() && $form->isValid()) {

                /** @var UploadedFile $coverPicture */
                $coverPicture = $form->get('coverPicture')->getData();
    
                $coverPictureFile = $form->get('coverPicture')->getData();
                if ($coverPictureFile) {
                    $coverPictureFileName = $fileUploader->upload($coverPictureFile);
                    $parcours->setCoverPicture($coverPictureFileName);
                }
            
                // Updates entity with data from form
                $parcours = $form->getData();
    
                // Saves entity in db
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($parcours);
                $entityManager->flush();
        
                // Redirects to list of biketours
                return $this->redirectToRoute('parcours');
            }

        } catch(\Exception $e) {
            error_log($e->getMessage());
        }

        return $this->render('parcours/newParcours.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * Retrieves data entered in a creation or update form
     */
    // public function getDataFromForm(Form $form, Parcours $parcours, Request $request, FileUploader $fileUploader){
                
        // if ($form->isSubmitted() && $form->isValid()) {

            // /** @var UploadedFile $coverPicture */
            /* $coverPicture = $form->get('coverPicture')->getData();

            $coverPictureFile = $form->get('coverPicture')->getData();
            if ($coverPictureFile) {
                $coverPictureFileName = $fileUploader->upload($coverPictureFile);
                $parcours->setCoverPicture($coverPictureFileName);
            }
        
            // Updates entity with data from form
            $parcours = $form->getData();

            // Saves entity in db
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($parcours);
            $entityManager->flush();

            $this->addFlash('success', 'Parcours ajouté');

            // Redirects to list of biketours
            return $this->redirectToRoute('parcours');
        }
    }*/


    /**
     * @Route("/delete/parcours/{id}/admin", name="delete_parcours", methods={"get", "delete"})
     */
    public function deleteParcours(Request $request, ParcoursRepository $repoP, $id){

        $parcours =$repoP->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($parcours);
        $entityManager->flush();

        return $this->redirectToRoute('parcours');

    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->redirectToRoute('parcours');
    }
}
