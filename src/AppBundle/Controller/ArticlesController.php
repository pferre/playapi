<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ArticlesController extends FOSRestController
{
    public function getArticlesAction()
    {
    }

    public function getArticleAction($id)
    {
        return [
            'title' =>  'TWAT',
            'body'  =>  'YOU ARE A TWAT'
        ];
    }

    public function postArticleAction()
    {
    }

    public function putArticlesAction()
    {
    }

    public function deleteArticlesAction()
    {
    }

}
