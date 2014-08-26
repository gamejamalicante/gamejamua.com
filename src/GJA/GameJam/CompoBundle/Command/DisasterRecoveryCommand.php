<?php

namespace GJA\GameJam\CompoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisasterRecoveryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('gamejam:compo:dr');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getContainer()->get('doctrine.dbal.disaster_recovery_connection');

        $games = $connection->query("SELECT * FROM gamejam_games;")->fetchAll();

        foreach ($games as $game) {
            $game = (object) $game;

            if(empty($game->image))
                continue;

            $output->writeln('UPDATE gamejam_games SET oldUrl = \'' .$game->image. '\' WHERE id = ' .$game->id. '');
        }
    }
}
