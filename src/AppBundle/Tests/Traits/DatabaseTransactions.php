<?php


namespace AppBundle\Tests\Traits;


trait DatabaseTransactions
{
    public function setUp()
    {
        exec('app/console doctrine:migrations:migrate --no-interaction -e=test');
    }

    public function tearDown()
    {
        exec('app/console doctrine:database:drop -e=test');
        exec('app/console doctrine:database:create -e=test');
    }
}