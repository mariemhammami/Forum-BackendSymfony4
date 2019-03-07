<?php

namespace App\Controller;
use App\Entity\Forum;
use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/categories")
 */
class CategoriesController extends AbstractController
{
    /**
     *
     * @Route("/", name="categories_index", methods={"GET"}, defaults={"_format": "json"})
     */
    public function index()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $cat = $em->getRepository(Categories::class)->findAll();
        return View::create($cat, Response::HTTP_CREATED, []);
    }

    /**
     * @Route("/new", name="categories_new", methods={"POST"}, defaults={"_format": "json"})
     */
    public function new(Request $request)
    {

        $cat = new Categories();
        $forum = new Forum();

        $cat->setNomCategories($request->get('nom_categories'));
        $entityManager = $this->getDoctrine()->getManager();
        $form = $entityManager->getRepository(Forum::class)->find($request->get('id_forum'));
        $cat ->setForum($form);
        $em = $this->getDoctrine()->getManager();
        $em->persist($cat);
        $em->flush();
        return View::create($cat, Response::HTTP_CREATED , []);
    }
    /**
     * @Route("/{id}", name="categories_show", methods={"GET"}, defaults={"_format": "json"})
     */
    public function show($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cat = $entityManager->getRepository(Categories::class)->find($id);
        return View::create($cat, Response::HTTP_OK , []);

    }
    /**
     * @Route("/{id}/edit", name="categories_edit", methods={"PUT"}, defaults={"_format": "json"})
     */
    public function edit(Request $request, Categories $category)
    {
        $cat = $this->getDoctrine()->getRepository(Categories::class)->find($request->get('id'));

        $cat->setNomCategories($request->get('nom_categories'));
        $entityManager = $this->getDoctrine()->getManager();
        $form = $entityManager->getRepository(Forum::class)->find($request->get('id_forum'));
        $cat ->setForum($form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cat);
        $em->flush();
        return View::create($cat, Response::HTTP_CREATED , []);
    }

    /**
     * @Route("/{id}", name="categories_delete", methods={"DELETE"}, defaults={"_format": "json"})
     */
    public function delete(Request $request, Categories $category)
    {
        $cat = $this->getDoctrine()->getRepository(Categories::class)->find($request->get('id'));
        if (empty($cat)) {
            $response =array(
                'message' => "Post NOt Found"
            );
            return new JsonResponse($response,Response::HTTP_NOT_FOUND);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cat);
        $entityManager->flush();
        return View::create($cat, Response::HTTP_CREATED , []);
    }
}
