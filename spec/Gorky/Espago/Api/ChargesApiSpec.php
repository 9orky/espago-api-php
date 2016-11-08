<?php

namespace spec\Gorky\Espago\Api;

use Gorky\Espago\Api\ChargesApi;
use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Handler\AbstractResponseHandler;
use Gorky\Espago\Http\HttpCall;
use Gorky\Espago\Http\HttpClient;
use Gorky\Espago\Http\HttpResponse;
use Gorky\Espago\Model\Response\Charge;
use Gorky\Espago\Model\Response\Client;
use Gorky\Espago\Model\Response\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChargesApiSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, HttpCallFactory $httpCallFactory, AbstractResponseHandler $responseHandler)
    {
        $this->beConstructedWith($httpClient, $httpCallFactory, $responseHandler);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChargesApi::class);
    }

    function it_will_create_authorization_by_token(
        Token $token,
        Charge $charge,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $token->getTokenValue()->willReturn(Argument::type('string'));

        $httpCallFactory->buildPostCall(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($charge);

        $this->createAuthorizationByToken(
            $token,
            6.66,
            Argument::type('string'),
            Argument::type('string')
        )->shouldReturn($charge);
    }

    function it_will_create_authorization_by_client(
        Client $client,
        Charge $charge,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $client->getId()->willReturn(Argument::type('string'));

        $httpCallFactory->buildPostCall(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($charge);

        $this->createAuthorizationByClient(
            $client,
            6.66,
            Argument::type('string'),
            Argument::type('string')
        )->shouldReturn($charge);
    }

    function it_will_capture_authorization(
        Charge $charge,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $httpCallFactory->buildPostCall(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($httpCall);

        $httpClient->makeCall(
            $httpCall,
            Argument::type('closure') # hack due to api error response different formats
        )->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($charge);

        $this->captureAuthorization(
            Argument::type('string'),
            6.66
        )->shouldReturn($charge);
    }

    function it_will_create_charge_by_token(
        Token $token,
        Charge $charge,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $token->getTokenValue()->willReturn(Argument::type('string'));

        $httpCallFactory->buildPostCall(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($charge);

        $this->createChargeByToken(
            $token,
            6.66,
            Argument::type('string'),
            Argument::type('string')
        )->shouldReturn($charge);
    }

    function it_will_create_charge_by_client(
        Client $client,
        Charge $charge,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $client->getId()->willReturn(Argument::type('string'));

        $httpCallFactory->buildPostCall(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($charge);

        $this->createAuthorizationByClient(
            $client,
            6.66,
            Argument::type('string'),
            Argument::type('string')
        )->shouldReturn($charge);
    }

    function it_will_get_existing_charge(
        Charge $charge,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $httpCallFactory->buildGetCall(Argument::type('string'))->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($charge);

        $this->getCharge(Argument::type('string'))->shouldReturn($charge);
    }

    function it_will_refund_charge(
        Charge $charge,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $httpCallFactory->buildPostCall(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($charge);

        $this->refundCharge(
            Argument::type('string'),
            6.66
        )->shouldReturn($charge);
    }

    function it_will_cancel_charge(
        Charge $charge,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $httpCallFactory->buildDeleteCall(Argument::type('string'))->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($charge);

        $this->cancelCharge(Argument::type('string'))->shouldReturn($charge);
    }
}
