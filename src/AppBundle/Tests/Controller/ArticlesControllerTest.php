<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Traits\DatabaseTransactions;
use AppBundle\Tests\Traits\Payloads;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    use DatabaseTransactions, Payloads;

    protected $em;

    protected $client;

    /**
     * ArticlesControllerTest constructor.
     */
    public function __construct()
    {
        self::bootKernel();

        $this->em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->client = static::createClient();
    }


    public function test_get_all_articles()
    {
        $this->client->request('GET','/api/articles');

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $articles = $this->em->getRepository('AppBundle:Article')
            ->findAll();

        $this->assertCount(count($articles), json_decode($response->getContent(), true));
    }

    public function test_it_returns_specific_article()
    {
        $article = $this->em->getRepository('AppBundle:Article')
            ->findOneBy(['id' => 2]);

        $this->client->request('GET', '/api/articles/'.json_encode($article->getId()));

        $response = $this->client->getResponse();

        $this->assertContains($article->getBody(), json_decode($response->getContent(), true));
    }

    public function test_it_returns_a_404_when_a_specific_article_is_not_found()
    {
        $this->client->request('GET', '/api/articles/'.'11');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_creates_a_new_article()
    {
        $json = $this->createJSONPayloadForNewArticle();

        $this->postNewArticle($json);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $article = $this->em->getRepository('AppBundle:Article')
            ->findOneBy(['id' => 11]);

        $this->assertContains($article->getBody(), $json);
    }

    public function test_it_updates_an_existing_article()
    {
        $json = $this->createJSONPayloadForNewArticle();

        $this->postNewArticle($json);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $article = $this->em->getRepository('AppBundle:Article')
            ->findOneBy(['id' => 11]);

        $json = $this->createJSONPayloadForUpdateArticle();

        $this->client->request('PUT', '/api/articles/'.$article->getId(), [], [], [], $json);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_returns_a_404_when_article_to_be_updated_is_not_found()
    {
        $json = $this->createJSONPayloadForUpdateArticle();

        $this->client->request('PUT', '/api/articles/'.'11', [], [], [], $json);

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_returns_an_unprocessable_entity_error_when_payload_is_not_valid()
    {
        $json = $this->createInvalidJSONPayload();

        $this->postNewArticle($json);

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_deletes_an_article()
    {
        $article = $this->em->getRepository('AppBundle:Article')
            ->findOneBy(['id' => 1]);

        $this->client->request('DELETE', '/api/articles/'.$article->getId());

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @param $json
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    private function postNewArticle($json)
    {
        $this->client->request('POST', '/api/articles', [], [], [], $json);
    }

}
