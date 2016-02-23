<?php

namespace Gorky\Espago\Command;

use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Exception\Api\ResourceNotFoundException;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ChargeCustomerCommand extends EspagoCommand
{
    protected function configure()
    {
        $this->setDescription('Charge Customer');

        $this->setDefinition(new InputDefinition([
            new InputOption('interactive'),
            new InputOption('authorize'),
            new InputOption('capture'),
            new InputOption('cancel'),
            new InputOption('clientId', null, InputOption::VALUE_OPTIONAL),
            new InputOption('amount', null, InputOption::VALUE_OPTIONAL),
            new InputOption('currency', null, InputOption::VALUE_OPTIONAL),
            new InputOption('description', null, InputOption::VALUE_OPTIONAL),
            new InputOption('chargeId', null, InputOption::VALUE_OPTIONAL)
        ]));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initiateSymfonyStyle($input, $output);

        if ($input->getOption('capture')) {
            list($chargeId, $amount) = $this->handleCaptureArguments($input);

            return $this->runCaptureProcedure($chargeId, $amount);
        }

        if ($input->getOption('cancel')) {

        }

        list($clientId, $amount, $currency, $description) = $this->handleChargeArguments($input);

        if ($input->getOption('authorize')) {
            $this->runAuthorizationProcedure($clientId, $amount, $currency, $description);
        }

        return $this->runChargeProcedure($clientId, $amount, $currency, $description);
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     *
     * @throws \Exception
     */
    private function handleChargeArguments(InputInterface $input)
    {
        if ($input->getOption('interactive')) {
            return [
                $this->io->ask('Customer ID'),
                $this->io->ask('Amount'),
                $this->io->ask('Currency', 'PLN'),
                $this->io->ask('Description', 'Test charge')
            ];
        }

        $clientId = $input->getOption('clientId');
        $amount = $input->getOption('amount');
        $currency = $input->getOption('currency');
        $description = $input->getOption('description');

        if (!$clientId || !$amount || !$currency || !$description) {
            throw new \Exception('New Charge must contain: clientId, amount, currency and description.');
        }

        return [$clientId, $amount, $currency, $description];
    }

    /**
     * @param string $customerId
     * @param string $amount
     * @param string $currency
     * @param string $description
     *
     * @return int
     */
    private function runAuthorizationProcedure($customerId, $amount, $currency, $description)
    {
        $this->io->section('Create new Authorization Hold');

        $clientsApi = $this->apiFactory->buildClientsApi();

        try {

            $this->io->write("[1/2] Checking Client ID...\t");

            $customer = $clientsApi->getClient($customerId)->getClient();

            $this->io->writeln(sprintf('Client found: %s', $customer->getId()));

            $chargesApi = $this->apiFactory->buildChargesApi();

            $this->io->write("[2/2] Trying to create a new Authorization...\t");

            $createAuthorizationResponse = $chargesApi->createAuthorizationByClient(
                $customer,
                $amount,
                $currency,
                $description
            );

            if ($createAuthorizationResponse->isIs3dSecure()) {
                $this->io->writeln('[HOLD]');
                $this->io->caution(
                    sprintf(
                        '3DSecure is enabled. Please visit this URL to continue: %s',
                        $createAuthorizationResponse->getRedirectUrl()
                    )
                );

                return 1;
            }

            $this->io->writeln(sprintf('[OK] Done: %s', $createAuthorizationResponse->getId()));
            $this->io->success('Authorization successful');

            $this->printChargeSummary(
                $createAuthorizationResponse->getId(),
                $createAuthorizationResponse->getClient(),
                $createAuthorizationResponse->getAmount(),
                $createAuthorizationResponse->getCurrency(),
                $createAuthorizationResponse->getState()
            );
        } catch (BadRequestException $e) {
            $this->io->error('Bad request to Espago API');
            $this->io->listing($e->getBadRequestErrors());

            return 1;
        } catch (ResourceNotFoundException $e) {
            $this->io->error('Client was not found');

            return 1;
        }

        return 0;
    }

    /**
     * @param string $customerId
     * @param string $amount
     * @param string $currency
     * @param string $description
     *
     * @return int
     */
    private function runChargeProcedure($customerId, $amount, $currency, $description)
    {
        $this->io->section('Create new Charge');

        $clientsApi = $this->apiFactory->buildClientsApi();

        try {
            $this->io->write("[1/2] Checking Client ID...\t");

            $customer = $clientsApi->getClient($customerId)->getClient();

            $this->io->writeln(sprintf('Client found: %s', $customer->getId()));

            $chargesApi = $this->apiFactory->buildChargesApi();

            $this->io->write("[2/2] Trying to create a new Charge...\t");

            $createChargeResponse = $chargesApi->createChargeByClient($customer, $amount, $currency, $description);

            if ($createChargeResponse->isIs3dSecure()) {
                $this->io->caution(
                    sprintf(
                        '3DSecure is enabled. Please visit this URL to continue: %s',
                        $createChargeResponse->getRedirectUrl()
                    )
                );

                return 1;
            }

            $this->io->writeln(sprintf('[OK] Done: %s', $createChargeResponse->getId()));
            $this->io->success('Customer was charged successfully');

            $this->printChargeSummary(
                $createChargeResponse->getId(),
                $createChargeResponse->getClient(),
                $createChargeResponse->getAmount(),
                $createChargeResponse->getCurrency(),
                $createChargeResponse->getState()
            );
        } catch (BadRequestException $e) {
            $this->io->error('Bad request to Espago API');
            $this->io->listing($e->getBadRequestErrors());

            return 1;
        } catch (ResourceNotFoundException $e) {
            $this->io->error('Client was not found');

            return 1;
        }

        return 0;
    }

    /**
     * @param string $id
     * @param string $clientId
     * @param string $amount
     * @param string $currency
     * @param string $state
     */
    private function printChargeSummary($id, $clientId, $amount, $currency, $state)
    {
        $headers = [
            'Charge ID', 'Client ID', 'Amount', 'Currency', 'State'
        ];

        $rows = [[
            $id, $clientId, $amount, $currency, $state
        ]];

        $this->io->table($headers, $rows);
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     *
     * @throws \Exception
     */
    private function handleCaptureArguments(InputInterface $input)
    {
        if ($input->getOption('interactive')) {
            return [
                $this->io->ask('Customer ID'),
                $this->io->ask('amount')
            ];
        }

        $chargeId = $input->getOption('chargeId');
        $amount = $input->getOption('amount');

        if (!$chargeId || !$amount) {
            throw new \Exception('Required arguments are Charge ID and amount to capture');
        }

        return [$chargeId, $amount];
    }

    /**
     * @param string $chargeId
     * @param float $amount
     *
     * @return int
     */
    private function runCaptureProcedure($chargeId, $amount)
    {
        $this->io->section('Capture Authorization Hold');

        $chargesApi = $this->apiFactory->buildChargesApi();

        try {
            $this->io->write("[1/2] Checking Authorization ID...\t");

            $getChargeResponse = $chargesApi->getCharge($chargeId);

            $this->io->writeln('[OK] Charge ID was found');

            $this->io->write(sprintf("[2/2] Capturing %.2f...\t", $amount));

            $chargesApi->captureAuthorization($getChargeResponse->getId(), $amount);

            $this->io->writeln('[OK] Done');
            $this->io->success('Capturing authorized funds was successful');
        } catch (BadRequestException $e) {
            $this->io->writeln('[FAILED] Operation Failed');
            $this->io->error('Bad request to Espago API');
            $this->io->listing($e->getBadRequestErrors());

            return 1;
        } catch (ResourceNotFoundException $e) {
            $this->io->error('Client was not found');

            return 1;
        }

        return 0;
    }
}