<?php

declare(strict_types=1);

namespace Gorky\Espago\Command;

use Gorky\Espago\Factory\ApiFactory;
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
     * @var ApiFactory
     */
    protected $apiFactory;

    /**
     * @param null|string $name
     * @param ApiFactory $apiFactory
     */
    public function __construct($name, ApiFactory $apiFactory)
    {
        parent::__construct($name);

        $this->apiFactory = $apiFactory;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function initiateSymfonyStyle(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title(sprintf('Espago v3, today is: %s', (new \DateTime())->format('l jS \of F Y h:i A')));
    }
}