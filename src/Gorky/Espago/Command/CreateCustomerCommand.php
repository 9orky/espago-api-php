<?php

namespace Gorky\Espago\Command;

use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Exception\Api\MalformedResponseException;
use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCustomerCommand extends EspagoCommand
{
    protected function configure()
    {
        $this->setDescription('Create Customer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initiateSymfonyStyle($input, $output);

        $this->io->section('Provide Customer data');

        $customerEmail = $this->io->ask('Email', 'test@example.com');
        $customerDescription = $this->io->ask('Description', '-');

        $this->io->section('Provide Card details');

        $cardFirstName = $this->io->ask('First name', 'Tom');
        $cardLastName = $this->io->ask('Last name', 'Starling');
        $cardNumber = $this->io->ask('Card Number', '4242424242424242');
        $cardMonth = $this->io->ask('Expiration month', '02');
        $cardYear = $this->io->ask('Expiration year', '2018');
        $cardCvv = $this->io->ask('CVV', '123');

        try {
            $tokensApi = $this->apiFactory->buildTokensApi();

            $unauthorizedCard = $tokensApi->createUnauthorizedCard(
                $cardNumber,
                $cardFirstName,
                $cardLastName,
                $cardMonth,
                $cardYear,
                $cardCvv
            );

            $this->io->section('Create Customer');

            $this->io->write('[1/2] Obtaining Token in exchange of Card data... ');

            $token = $tokensApi->createToken($unauthorizedCard);

            $this->io->writeln(sprintf('[OK] Token obtained successfully: %s', $token));

            $clientsApi = $this->apiFactory->buildClientsApi();

            $this->io->write('[2/2] Creating new Client using Token... ');

            $customer = $clientsApi->createClient($token, $customerEmail, $customerDescription);

            $this->io->writeln(sprintf('[OK] Client created successfully: %s', $customer->getClient()->getId()));

            $this->io->success('Customer created successfully');

            $this->printCustomerSummaryTable($customer->getClient(), $customer->getCard());
        } catch (BadRequestException $e) {
            $this->io->error('Bad request to Espago API');
            $this->io->listing($e->getBadRequestErrors());
        } catch (MalformedResponseException $e) {
            $this->io->error('Response from Espago API is malformed. Try again later.');
        }
    }

    /**
     * @param Client $client
     * @param Card $card
     */
    private function printCustomerSummaryTable(Client $client, Card $card)
    {
        $headers = [
            'ID',
            'Email',
            'Description',
            'Name On Card',
            'Card Number',
            'Expiration Month',
            'Expiration Year',
            'Created at'
        ];

        $rows = [[
            $client->getId(),
            $client->getEmail(),
            $client->getDescription(),
            $card->getFirstAndLastName(),
            $card->getObfuscatedNumber(),
            $card->getMonth(),
            $card->getYear(),
            $card->getCreatedAt()->format('l jS \of F Y h:i:s A')
        ]];

        $this->io->table($headers, $rows);
    }
}