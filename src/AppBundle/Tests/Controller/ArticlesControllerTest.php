<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Traits\DatabaseTransactions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    use DatabaseTransactions;

    protected $em;

    /**
     * ArticlesControllerTest constructor.
     */
    public function __construct()
    {
        self::bootKernel();
        $this->em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


    public function test_get_all_articles()
    {
        $client = static::createClient();

        $client->request('GET','/api/articles');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $articles = $this->em->getRepository('AppBundle:Article')
            ->findAll();

        $this->assertCount(count($articles), json_decode($response->getContent(), true));
    }

    public function test_it_returns_specific_article()
    {
        $client = static::createClient();

        $article = $this->em->getRepository('AppBundle:Article')
            ->findOneBy(['id' => 2]);

        $client->request('GET', '/api/articles/'.json_encode($article->getId()));

        $response = $client->getResponse();

        $this->assertContains($article->getBody(), json_decode($response->getContent(), true));
    }

}
