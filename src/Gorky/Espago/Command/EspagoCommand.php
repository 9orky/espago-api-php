<?php

namespace Gorky\Espago\Command;

use Gorky\Espago\ApiProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EspagoCommand extends Command
{
    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var ApiProvider
     */
    protected $apiProvider;

    /**
     * @param null|string $name
     * @param ApiProvider $apiProvider
     */
    public function __construct($name, ApiProvider $apiProvider)
    {
        parent::__construct($name);

        $this->apiProvider = $apiProvider;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initiateSymfonyStyle(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title(sprintf('Espago v3, today is: %s', (new \DateTime())->format('l jS \of F Y h:i A')));
    }
}