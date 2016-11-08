<?php

namespace spec\Gorky\Espago\Api;

use Gorky\Espago\Api\ClientsApi;
use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Handler\AbstractResponseHandler;
use Gorky\Espago\Http\HttpCall;
use Gorky\Espago\Http\HttpClient;
use Gorky\Espago\Http\HttpResponse;
use Gorky\Espago\Model\Response\Client;
use Gorky\Espago\Model\Response\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientsApiSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, HttpCallFactory $httpCallFactory, AbstractResponseHandler $responseHandler)
    {
        $this->beConstructedWith($httpClient, $httpCallFactory, $responseHandler);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ClientsApi::class);
    }

    function it_will_create_a_new_client(
        Token $token,
        Client $client,
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

        $responseHandler->handle($httpResponse)->willReturn($client);

        $this->createClient($token)->shouldReturn($client);
    }

    function it_will_provide_existing_client(
        Client $client,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $httpCallFactory->buildGetCall(
            Argument::type('string')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($client);

        $this->getClient(Argument::type('string'))->shouldReturn($client);
    }

    function it_will_update_client(
        Token $token,
        Client $client,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $client->getId()->willReturn(Argument::type('string'));

        $token->getTokenValue()->willReturn(Argument::type('string'));

        $httpCallFactory->buildPutCall(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($client);

        $this->updateClient($client, $token)->shouldReturn($client);
    }

    function it_will_remove_client(
        Client $client,
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler
    ) {
        $client->getId()->willReturn(Argument::type('string'));

        $httpCallFactory->buildDeleteCall(
            Argument::type('string')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $httpResponse->getStatusCode()->willReturn(204);

        $responseHandler->handle($httpResponse)->willReturn($client);

        $this->removeClient($client)->shouldReturn(true);
    }
}
