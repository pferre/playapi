<?php


namespace AppBundle\Tests\Traits;


trait DatabaseTransactions
{
    public function setUp()
    {
        exec('app/console doctrine:database:drop --force --env=test');
        exec('app/console doctrine:database:create --env=test');
        exec('app/console doctrine:migrations:migrate --no-interaction --env=test');
        exec('app/console doctrine:fixtures:load --no-interaction --env=test');
    }
}