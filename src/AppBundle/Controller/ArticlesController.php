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

        $errors = $this->createAndSubmitForm($request, $article);

        if (count($errors) > 0) {
            return $this->view(
                $errors,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->persist($article);

        return $this->view($article, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return \FOS\RestBundle\View\View
     */
    public function putArticlesAction(Request $request, Article $article)
    {
        $errors = $this->createAndSubmitForm($request, $article);

        if (count($errors) > 0) {
            return $this->view(
                $errors,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->persist($article);

        return $this->view($article, Response::HTTP_CREATED);
    }

    /**
     * @param Article $article
     */
    public function deleteArticlesAction(Article $article)
    {
        $this->remove($article);
    }

    /**
     * @param Request $request
     * @param $article
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    private function createAndSubmitForm(Request $request, $article)
    {
        $form = $this->createForm(
            new ArticleType(),
            $article
        );

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        $errors = $this->get('validator')->validate($article);
        return $errors;
    }

    /**
     * @param $article
     */
    private function persist($article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
    }

    /**
     * @param Article $article
     */
    private function remove(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
    }

}
