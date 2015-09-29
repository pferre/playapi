<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    public function testGetarticles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/articles');
    }

    public function testGetarticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/articles/{id}');
    }

    public function testPostarticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/articles');
    }

    public function testUpdatearticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/articles');
    }

    public function testDeletearticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/articles');
    }

}
