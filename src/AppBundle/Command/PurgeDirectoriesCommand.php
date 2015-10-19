<?php


namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeDirectoriesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('command:purge:dir')
             ->setDescription('Delete some directories');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $target_dir = "web/www";
        $temp = [];

        exec("find ".$target_dir, $entries);

        foreach($entries as $dir_entry) {
            if (strstr($dir_entry, 'releases')) {
                $temp[] = $dir_entry;
            }
        }

        foreach($temp as $t) {
            dump(explode('/', $t));
        }

    }
}