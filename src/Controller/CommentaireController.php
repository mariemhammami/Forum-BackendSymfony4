<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Sujet;
use App\Entity\User;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\View\View;

/**
 * @Route("/commentaire")
 */
class CommentaireController extends AbstractController
{
    /**
     * @Route("/", name="commentaire_index", methods={"GET"}, defaults={"_format": "json"})
     */
    public function index(CommentaireRepository $commentaireRepository)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $comm = $em->getRepository(Commentaire::class)->findAll();
        return View::create($comm, Response::HTTP_CREATED, []);
    }

    /**
     * @Route("/new", name="commentaire_new", methods={"POST"}, defaults={"_format": "json"})
     */
    public function new(Request $request)
    {

        $comm = new Commentaire();

        $comm->setContenu($request->get('contenu'));
        $comm->setDate(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($request->get('id_user'));
        $suj= $entityManager->getRepository(Sujet::class)->find($request->get('id_sujet'));


        $comm ->setUser($user);
        $comm ->setSujet($suj);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comm);
        $em->flush();
        return View::create($comm, Response::HTTP_CREATED , []);
    }

    /**
     * @Route("/{id}", name="commentaire_show", methods={"GET"}, defaults={"_format": "json"})
     */
    public function show($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comm = $entityManager->getRepository(Commentaire::class)->find($id);
        return View::create($comm, Response::HTTP_OK , []);
    }

    /**
     * @Route("/{id}/edit", name="commentaire_edit", methods={"PUT"}, defaults={"_format": "json"})
     */
    public function edit(Request $request, Commentaire $commentaire)
    {
        $comm = $this->getDoctrine()->getRepository(Commentaire::class)->find($request->get('id'));

        $comm->setContenu($request->get('contenu'));
        $comm->setDate(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($request->get('id_user'));
        $suj= $entityManager->getRepository(Sujet::class)->find($request->get('id_sujet'));


        $comm ->setUser($user);
        $comm ->setSujet($suj);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comm);
        $em->flush();
        return View::create($comm, Response::HTTP_CREATED , []);
    }

    /**
     * @Route("/{id}", name="commentaire_delete", methods={"DELETE"}, defaults={"_format": "json"})
     */
    public function delete(Request $request,$id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comm = $entityManager->getRepository(Commentaire::class)->find($id);
        if (empty($comm)) {
            $response =array(
                'message' => "Post NOt Found"
            );
            return new JsonResponse($response,Response::HTTP_NOT_FOUND);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comm);
        $entityManager->flush();
        return View::create($comm, Response::HTTP_CREATED , []);
    }
}
