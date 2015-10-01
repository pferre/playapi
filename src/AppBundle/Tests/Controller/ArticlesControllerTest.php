<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Traits\DatabaseTransactions;
use AppBundle\Tests\Traits\Payloads;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    use DatabaseTransactions, Payloads;

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

    public function test_it_returns_a_404_when_a_specific_article_is_not_found()
    {
        $client = static::createClient();

        $client->request('GET', '/api/articles/'.'11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function test_it_creates_a_new_article()
    {
        $json = $this->createJSONPayloadForNewArticle();

        $client = $this->postNewArticle($json);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $article = $this->em->getRepository('AppBundle:Article')
            ->findOneBy(['id' => 11]);

        $this->assertContains($article->getBody(), $json);
    }

    public function test_it_updates_an_existing_article()
    {
        $json = $this->createJSONPayloadForNewArticle();

        $client = $this->postNewArticle($json);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $article = $this->em->getRepository('AppBundle:Article')
            ->findOneBy(['id' => 11]);

        $json = $this->createJSONPayloadForUpdateArticle();

        $client = static::createClient();

        $client->request('PUT', '/api/articles/'.$article->getId(), [], [], [], $json);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function test_it_returns_a_404_when_article_to_be_updated_is_not_found()
    {
        $json = $this->createJSONPayloadForUpdateArticle();

        $client = static::createClient();

        $client->request('PUT', '/api/articles/'.'11', [], [], [], $json);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function test_it_returns_an_unprocessable_entity_error_when_payload_is_not_valid()
    {
        $json = $this->createInvalidJSONPayload();

        $client = $this->postNewArticle($json);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    /**
     * @param $json
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    private function postNewArticle($json)
    {
        $client = static::createClient();

        $client->request('POST', '/api/articles', [], [], [], $json);

        return $client;
    }

}
