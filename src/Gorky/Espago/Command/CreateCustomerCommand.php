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

        $customerEmail = $this->io->ask('Email', 'test@example.com', function($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Provide a valid email address');
            }

            return $answer;
        });

        $customerDescription = $this->io->ask('Description', '-');

        $this->io->section('Provide Card details');

        $cardFirstName = $this->io->ask('First name', 'Tom', function($answer) {
            if (is_numeric($answer) || strlen($answer) < 2 || strlen($answer) > 255) {
                throw new \RuntimeException('First name must be a string between 2 and 255 characters');
            }

            return $answer;
        });

        $cardLastName = $this->io->ask('Last name', 'Starling', function($answer) {
            if (is_numeric($answer) || strlen($answer) < 2 || strlen($answer) > 255) {
                throw new \RuntimeException('Last name must be a string between 2 and 255 characters');
            }

            return $answer;
        });

        $cardNumber = $this->io->ask('Card Number', '4242424242424242',  function($answer) {
            if (!is_numeric($answer) || strlen($answer) !== 16) {
                throw new \RuntimeException('Card number must be 16 characters long number');
            }

            return $answer;
        });

        $cardMonth = $this->io->ask('Expiration month', '02', function($answer) {
            $month = (int) $answer;
            if ($month < 1 || $month > 12) {
                throw new \RuntimeException('Expiration month must be between 1 and 12');
            }

            return $answer;
        });

        $cardYear = $this->io->ask('Expiration year', '2018', function($answer) {
            $year = (int) $answer;
            $currentYear = (int) (new \DateTime())->format('Y');

            if ($year < $currentYear || abs($year - $currentYear) > 10) {
                throw new \RuntimeException('Wrong year');
            }

            return $answer;
        });

        $cardCvv = $this->io->ask('CVV', '123', function($answer) {
            if (!is_numeric($answer) || strlen($answer) !== 3) {
                throw new \RuntimeException('Wrong code');
            }

            return $answer;
        });

        try {
            $tokensApi = $this->apiProvider->getTokensApi();

            $unauthorizedCard = $tokensApi->createUnauthorizedCard(
                $cardNumber,
                $cardFirstName,
                $cardLastName,
                $cardMonth,
                $cardYear,
                $cardCvv
            );

            $this->io->section('Create Customer');

            $this->io->write("[1/2] Obtaining Token in exchange of Card data... \t");

            $token = $tokensApi->createToken($unauthorizedCard);

            $this->io->writeln(sprintf('[*] Token obtained successfully: %s', $token));

            $clientsApi = $this->apiProvider->getClientsApi();

            $this->io->write("[2/2] Creating new Client using Token... \t\t");

            $customer = $clientsApi->createClient($token, $customerEmail, $customerDescription);

            $this->io->writeln(sprintf('[*] Client created successfully: %s', $customer->getId()));

            $this->io->success('Client created successfully');

            $this->printCustomerSummaryTable($customer, $customer->getCard());
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