<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticlesController extends FOSRestController
{
    public function getArticlesAction()
    {
        $articles = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findAll();

        return $articles;
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function getArticleAction(Article $article)
    {
        return $article;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function postArticleAction(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(
            new ArticleType(),
            $article
        );

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        $errors = $this->get('validator')->validate($article);

        if (count($errors) > 0) {
            return $this->view(
                $errors,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return $this->view($article, Response::HTTP_CREATED);
    }

    public function putArticlesAction()
    {
    }

    public function deleteArticlesAction()
    {
    }

}
