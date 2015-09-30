<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Article;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {

            $article = new Article();
            $article->setTitle($faker->sentence());
            $article->setBody($faker->paragraph);

            $manager->persist($article);
            $manager->flush();
        }
    }
}