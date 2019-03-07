<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Sujet;
use App\Entity\User;
use App\Form\SujetType;
use App\Repository\SujetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\View\View;

/**
 * @Route("/sujet")
 */
class SujetController extends AbstractController
{
    /**
     * @Route("/", name="sujet_index", methods={"GET"}, defaults={"_format": "json"})
     */
    public function index(SujetRepository $sujetRepository)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $cat = $em->getRepository(Sujet::class)->findAll();
        return View::create($cat, Response::HTTP_CREATED, []);
    }

    /**
     * @Route("/new", name="sujet_new", methods={"POST"}, defaults={"_format": "json"})
     */
    public function new(Request $request)
    {
        $sujet = new Sujet();


        $sujet->setTitreSujet($request->get('titre_sujet'));
        $sujet->setContenuSujet($request->get('contenu'));
        $sujet->setDate(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $cat = $entityManager->getRepository(Categories::class)->find($request->get('id_cat'));
        $user = $entityManager->getRepository(User::class)->find($request->get('id_user'));


        $sujet ->setCategories($cat);
        $sujet ->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($sujet);
        $em->flush();
        return View::create($sujet, Response::HTTP_CREATED , []);
    }

    /**
     * @Route("/{id}", name="sujet_show", methods={"GET"}, defaults={"_format": "json"})
     */
    public function show($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sujet = $entityManager->getRepository(Sujet::class)->find($id);
        return View::create($sujet, Response::HTTP_OK , []);
    }

    /**
     * @Route("/{id}/edit", name="sujet_edit", methods={"PUT"}, defaults={"_format": "json"})
     */
    public function edit(Request $request)
    {
        $suj = $this->getDoctrine()->getRepository(Sujet::class)->find($request->get('id'));
        $suj->setTitreSujet($request->get('titre_sujet'));
        $suj->setContenuSujet($request->get('contenu'));
        $suj->setDate(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $cat = $entityManager->getRepository(Categories::class)->find($request->get('id_cat'));
        $user = $entityManager->getRepository(User::class)->find($request->get('id_user'));

        $suj ->setCategories($cat);
        $suj ->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($suj);
        $em->flush();
        return View::create($suj, Response::HTTP_CREATED , []);
    }

    /**
     * @Route("/{id}", name="sujet_delete", methods={"DELETE"}, defaults={"_format": "json"})
     */
    public function delete(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sujet = $entityManager->getRepository(Sujet::class)->find($id);
        if (empty($sujet)) {
            $response =array(
                'message' => "Post NOt Found"
            );
            return new JsonResponse($response,Response::HTTP_NOT_FOUND);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sujet);
        $entityManager->flush();
        return View::create($sujet, Response::HTTP_CREATED , []);
    }
}
