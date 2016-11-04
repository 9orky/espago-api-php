<?php

namespace spec\Gorky\Espago\Api;

use Gorky\Espago\Api\TokensApi;
use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Handler\AbstractResponseHandler;
use Gorky\Espago\Handler\TokenResponseHandler;
use Gorky\Espago\Http\HttpCall;
use Gorky\Espago\Http\HttpClient;
use Gorky\Espago\Http\HttpResponse;
use Gorky\Espago\Model\UnauthorizedCard;
use Gorky\Espago\Model\Response\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokensApiSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, HttpCallFactory $httpCallFactory, AbstractResponseHandler $responseHandler)
    {
        $this->beConstructedWith($httpClient, $httpCallFactory, $responseHandler);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TokensApi::class);
    }

    function it_will_return_an_unauthorized_card()
    {
        $this->createUnauthorizedCard(
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('string')
        )->shouldReturnAnInstanceOf(UnauthorizedCard::class);
    }

    function it_will_create_token(
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler,
        UnauthorizedCard $unauthorizedCard,
        Token $token
    ) {
        $unauthorizedCard->getNumber()->willReturn(Argument::type('string'));
        $unauthorizedCard->getFirstName()->willReturn(Argument::type('string'));
        $unauthorizedCard->getLastName()->willReturn(Argument::type('string'));
        $unauthorizedCard->getMonth()->willReturn(Argument::type('string'));
        $unauthorizedCard->getYear()->willReturn(Argument::type('string'));
        $unauthorizedCard->getCode()->willReturn(Argument::type('string'));

        $httpCallFactory->buildPostCallAuthorizedWithPublicKey(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($token);

        $this->createToken($unauthorizedCard)->shouldReturnAnInstanceOf(Token::class);
    }

    public function it_will_provide_existing_token(
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler,
        Token $token
    ) {
        $httpCallFactory->buildGetCall(Argument::type('string'))->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($token);

        $this->getToken(Argument::type('string'))->shouldReturnAnInstanceOf(Token::class);
    }
}
