<?php

namespace spec\Gorky\Espago\Api;

use Gorky\Espago\Api\CvvTokensApi;
use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Handler\AbstractResponseHandler;
use Gorky\Espago\Http\HttpCall;
use Gorky\Espago\Http\HttpClient;
use Gorky\Espago\Http\HttpResponse;
use Gorky\Espago\Model\Response\CvvToken;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CvvTokensApiSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, HttpCallFactory $httpCallFactory, AbstractResponseHandler $responseHandler)
    {
        $this->beConstructedWith($httpClient, $httpCallFactory, $responseHandler);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CvvTokensApi::class);
    }

    function it_will_create_cvv_token(
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler,
        CvvToken $cvvToken
    ) {
        $httpCallFactory->buildPostCallAuthorizedWithPublicKey(
            Argument::type('string')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($cvvToken);

        $this->createCvvToken()->shouldReturn($cvvToken);
    }

    public function it_will_provide_cvv_token(
        HttpCallFactory $httpCallFactory,
        HttpCall $httpCall,
        HttpClient $httpClient,
        HttpResponse $httpResponse,
        AbstractResponseHandler $responseHandler,
        CvvToken $cvvToken
    ) {
        $httpCallFactory->buildGetCall(
            Argument::type('string')
        )->willReturn($httpCall);

        $httpClient->makeCall($httpCall)->willReturn($httpResponse);

        $responseHandler->handle($httpResponse)->willReturn($cvvToken);

        $this->getCvvToken(Argument::type('string'))->shouldReturn($cvvToken);
    }
}