<?php

namespace Gorky\Espago\Command;

use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Exception\Api\ResourceNotFoundException;
use Gorky\Espago\Exception\Payment\PaymentOperationFailedException;
use Gorky\Espago\Exception\Payment\PaymentRejectedException;
use Gorky\Espago\Model\Response\Charge;
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
            new InputOption('refund'),
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

        if ($input->getOption('authorize')) {
            return $this->runCaptureProcedure($input);
        }

        if ($input->getOption('refund')) {
            return $this->runCancelProcedure($input);
        }

        if ($input->getOption('capture')) {
            return $this->runCancelProcedure($input);
        }

        if ($input->getOption('cancel')) {
            return $this->runAuthorizationProcedure($input);
        }

        return $this->runChargeProcedure($input);
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     *
     * @throws \RuntimeException
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
            throw new \RuntimeException('New Charge must contain: clientId, amount, currency and description.');
        }

        return [$clientId, $amount, $currency, $description];
    }

    /**
     * @param InputInterface $input
     *
     * @return int
     */
    private function runAuthorizationProcedure(InputInterface $input)
    {
        $this->io->section('Create new Authorization Hold');

        list($clientId, $amount, $currency, $description) = $this->handleChargeArguments($input);

        $clientsApi = $this->apiProvider->getClientsApi();

        try {

            $this->io->write("[1/2] Checking Client ID...\t\t\t");

            $customer = $clientsApi->getClient($clientId);

            $this->io->writeln(sprintf('[*] Client found: %s', $customer->getId()));

            $chargesApi = $this->apiProvider->getChargesApi();

            $this->io->write("[2/2] Trying to create a new Authorization...\t");

            $charge = $chargesApi->createAuthorizationByClient(
                $customer,
                $amount,
                $currency,
                $description
            );

            if ($charge->is3dSecure()) {
                return $this->handle3dSecure($charge);
            }

            if ($charge->mustMakeDccDecision()) {
                $chargesApi->makeDccDecision($charge->getId(), $this->handleDccDecision($charge));
            }

            $this->io->writeln(sprintf('[*] Done: %s', $charge->getId()));
            $this->io->success('Authorization successful');

            $this->printChargeSummary(
                $charge->getId(),
                $charge->getClientId(),
                $charge->getAmount(),
                $charge->getCurrency(),
                $charge->getState()
            );
        } catch (BadRequestException $e) {
            $this->io->error('Bad request to Espago API');
            $this->io->listing($e->getBadRequestErrors());

            return 1;
        } catch (ResourceNotFoundException $e) {
            $this->io->error('Client was not found');

            return 1;
        } catch (PaymentOperationFailedException $e) {
            $this->io->writeln('[ ] Payment operation failed');
            $this->io->error($e->getMessage());

            return 1;
        } catch (PaymentRejectedException $e) {
            $this->io->writeln('[ ] Payment operation was rejected');
            $this->io->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    /**
     * @param InputInterface $input
     *
     * @return int
     */
    private function runChargeProcedure(InputInterface $input)
    {
        $this->io->section('Create new Charge');

        list($clientId, $amount, $currency, $description) = $this->handleChargeArguments($input);

        $clientsApi = $this->apiProvider->getClientsApi();

        try {
            $this->io->write("[1/2] Checking Client ID...\t\t");

            $customer = $clientsApi->getClient($clientId);

            $this->io->writeln(sprintf('[*] Client found: %s', $customer->getId()));

            $chargesApi = $this->apiProvider->getChargesApi();

            $this->io->write("[2/2] Trying to create a new Charge...\t");

            $charge = $chargesApi->createChargeByClient($customer, $amount, $currency, $description);

            if ($charge->is3dSecure()) {
                return $this->handle3dSecure($charge);
            }

            if ($charge->mustMakeDccDecision()) {
                $charge = $chargesApi->makeDccDecision($charge->getId(), $this->handleDccDecision($charge));
            }

            $this->io->writeln(sprintf('[*] Success. Charge ID is: %s', $charge->getId()));
            $this->io->success('Customer was charged successfully');

            $this->printChargeSummary(
                $charge->getId(),
                $charge->getClientId(),
                $charge->getAmount(),
                $charge->getCurrency(),
                $charge->getState()
            );
        } catch (BadRequestException $e) {
            $this->io->writeln('[ ] Operation failed');
            $this->io->error('Bad request to Espago API');
            $this->io->listing($e->getBadRequestErrors());

            return 1;
        } catch (ResourceNotFoundException $e) {
            $this->io->writeln('[ ] Operation failed');
            $this->io->error('Client was not found');

            return 1;
        } catch (PaymentOperationFailedException $e) {
            $this->io->writeln('[ ] Payment operation failed');
            $this->io->error($e->getMessage());

            return 1;
        } catch (PaymentRejectedException $e) {
            $this->io->writeln('[ ] Payment operation was rejected');
            $this->io->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    /**
     * @param Charge $charge
     *
     * @return int
     */
    private function handle3dSecure(Charge $charge): int
    {
        $this->io->writeln(
            sprintf(
                '[~] Transaction: %s is 3d Secured, please visit: %s',
                $charge->getTransactionId(),
                $charge->getRedirectUrl()
            )
        );

        return 0;
    }

    /**
     * @param Charge $charge
     *
     * @return bool
     */
    private function handleDccDecision(Charge $charge): bool
    {
        $this->io->note('DCC exchange rate acceptance');

        $dccDecision = $charge->getDccDecision();

        $this->io->table([], [
            ['Cardholder currency name' => $dccDecision->getCardHolderCurrencyName()],
            ['Cardholder amount' => $dccDecision->getCardHolderAmount()],
            ['Conversion rate' => $dccDecision->getCardHolderCurrencyName()],
        ]);

        $dccDecision = $this->io->ask('Do you accept DCC exchange rate?', 'Y/N', function($answer) {
            if (!in_array($answer, ['Y', 'N'])) {
                throw new \RuntimeException('Valid answers are: Y or N');
            }

            return 'Y' === $answer;
        });

        return $dccDecision;
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
     * @throws \RuntimeException
     */
    private function handleCaptureArguments(InputInterface $input)
    {
        if ($input->getOption('interactive')) {
            return [
                $this->io->ask('Charge ID'),
                $this->io->ask('amount')
            ];
        }

        $chargeId = $input->getOption('chargeId');
        $amount = $input->getOption('amount');

        if (!$chargeId || !$amount) {
            throw new \RuntimeException('Required arguments are: Charge ID and amount to capture');
        }

        return [$chargeId, $amount];
    }

    /**
     * @param InputInterface $input
     *
     * @return int
     */
    private function runCaptureProcedure(InputInterface $input)
    {
        $this->io->section('Capture Authorization Hold');

        list($chargeId, $amount) = $this->handleCaptureArguments($input);

        $chargesApi = $this->apiProvider->getChargesApi();

        try {
            $this->io->write("[1/2] Checking Authorization Charge ID...\t");

            $chargesApi->getCharge($chargeId);

            $this->io->writeln(sprintf('[*] Charge was found by ID: %s', $chargeId));

            $this->io->write(sprintf("[2/2] Capturing %.2f...\t\t\t", $amount));

            $charge = $chargesApi->captureAuthorization($chargeId, $amount);

            $this->io->writeln('[*] Done');
            $this->io->success('Capturing authorized funds was successful');

            $this->printChargeSummary(
                $charge->getId(),
                $charge->getClientId(),
                $charge->getAmount(),
                $charge->getCurrency(),
                $charge->getState()
            );
        } catch (BadRequestException $e) {
            $this->io->writeln('[ ] Operation Failed');
            $this->io->error('Bad request to Espago API');
            $this->io->listing($e->getBadRequestErrors());

            return 1;
        } catch (ResourceNotFoundException $e) {
            $this->io->writeln('[ ] Operation Failed');
            $this->io->error('Client was not found');

            return 1;
        } catch (PaymentOperationFailedException $e) {
            $this->io->writeln('[ ] Payment operation failed');
            $this->io->error($e->getMessage());

            return 1;
        } catch (PaymentRejectedException $e) {
            $this->io->writeln('[ ] Payment operation was rejected');
            $this->io->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     */
    private function handleCancelArguments(InputInterface $input)
    {
        if ($input->getOption('interactive')) {
            return [
                $this->io->ask('Charge ID')
            ];
        }

        $chargeId = $input->getOption('chargeId');

        if (!$chargeId) {
            throw new \RuntimeException('Required argument is chargeId');
        }

        return [$chargeId];
    }

    /**
     * @param InputInterface $input
     *
     * @return int
     */
    private function runCancelProcedure(InputInterface $input)
    {
        $this->io->section('Cancel Charge');

        list($chargeId) = $this->handleCancelArguments($input);

        $chargesApi = $this->apiProvider->getChargesApi();

        try {
            $this->io->write("[1/2] Checking if Charge exists by ID...\t");

            $chargesApi->getCharge($chargeId);

            $this->io->writeln(sprintf('[*] Charge ID exists: %s', $chargeId));

            $this->io->write("[2/2] Cancelling Charge...\t\t\t");

            $charge = $chargesApi->cancelCharge($chargeId);

            $this->io->write('[*] Success');
            $this->io->success('Charge was cancelled successfully');

            $this->printChargeSummary(
                $charge->getId(),
                $charge->getClientId(),
                $charge->getAmount(),
                $charge->getCurrency(),
                $charge->getState()
            );
        } catch (BadRequestException $e) {
            $this->io->writeln('[ ] Operation Failed');
            $this->io->error('Bad request to Espago API');
            $this->io->listing($e->getBadRequestErrors());

            return 1;
        } catch (ResourceNotFoundException $e) {
            $this->io->writeln('[ ] Operation Failed');
            $this->io->error('Charge was not found');
            return 1;
        } catch (PaymentOperationFailedException $e) {
            $this->io->writeln('[ ] Payment operation failed');
            $this->io->error($e->getMessage());

            return 1;
        } catch (PaymentRejectedException $e) {
            $this->io->writeln('[ ] Payment operation was rejected');
            $this->io->error($e->getMessage());

            return 1;
        }

        return 0;
    }
}