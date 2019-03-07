<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Categories;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Controller\CategoriesController;
use FOS\RestBundle\View\View;
/**
 * @Route("/forum")
 */
class ForumController extends AbstractController
{
    /**
     * @Route("/", name="forum_index", methods={"GET"}, defaults={"_format": "json"})
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Forum::class);

        // query for a single Product by its primary key (usually "id")
        $article = $repository->findall();

        return View::create($article, Response::HTTP_OK , []);
    }

    /**
     * @Route("/new", name="forumNew", methods={"POST"}, defaults={"_format": "json"})
     */
    public function new(Request $request,ValidatorInterface $validator)
    {
        $forum = new Forum();

        $forum->setTitreForum($request->get('titre_forum'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($forum);
        $em->flush();
        return View::create($forum, Response::HTTP_CREATED , []);

   }

    /**
     * @Route("/{id}", name="forum_show", methods={"GET"}, defaults={"_format": "json"})
     */
    public function show($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $entityManager->getRepository(Forum::class)->find($id);

        return View::create($form, Response::HTTP_OK , []);

    }

    /**
     * @Route("/{id}/edit", name="forum_edit", methods={"PUT"}, defaults={"_format": "json"})
     */
    public function edit(Request $request, Forum $forum)
    {

        $forum = $this->getDoctrine()->getRepository(Forum::class)->find($request->get('id'));

        $forum->setTitreForum($request->get('titre_forum'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($forum);
        $em->flush();
        return View::create($forum, Response::HTTP_CREATED , []);
    }

    /**
     * @Route("/{id}", name="forum_delete", methods={"DELETE"}, defaults={"_format": "json"})
     */
    public function delete(Request $request, Forum $forum)
    {$forum = $this->getDoctrine()->getRepository(Forum::class)->find($request->get('id'));
        if (empty($forum)) {
            $response =array(
                'message' => "Post NOt Found"
            );
            return new JsonResponse($response,Response::HTTP_NOT_FOUND);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($forum);
        $entityManager->flush();
        return View::create($forum, Response::HTTP_CREATED , []);
    }


    public function promoteUser(){
       // $this->getUser()->addRole("ROLE_HERO");
    }
}
