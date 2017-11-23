<?php

namespace Lmc\Matej;

use Http\Mock\Client;
use Lmc\Matej\Model\Command\ItemPropertySetup;
use Lmc\Matej\Model\CommandResponse;

/**
 * @covers \Lmc\Matej\Matej
 */
class MatejTest extends TestCase
{
    /** @test */
    public function shouldBeInstantiable()
    {
        $matej = new Matej('accountId', 'apiKey');
        $this->assertInstanceOf(Matej::class, $matej);
    }

    /** @test */
    public function shouldExecuteRequestViaBuilder()
    {
        $dummyHttpResponse = $this->createJsonResponseFromFile(__DIR__ . '/Http/Fixtures/response-one-successful-command.json');
        $mockClient = new Client();
        $mockClient->addResponse($dummyHttpResponse);
        $matej = new Matej('accountId', 'apiKey');
        $matej->setHttpClient($mockClient);
        $response = $matej->request()->setupItemProperties()->addProperty(ItemPropertySetup::timestamp('valid_from'))->send();
        $this->assertCount(1, $mockClient->getRequests());
        $this->assertStringStartsWith('https://accountid.matej.lmc.cz/', $mockClient->getRequests()[0]->getUri()->__toString());
        $this->assertSame(1, $response->getNumberOfCommands());
        $this->assertSame(1, $response->getNumberOfSuccessfulCommands());
        $this->assertSame(0, $response->getNumberOfSkippedCommands());
        $this->assertSame(0, $response->getNumberOfFailedCommands());
        $this->assertCount(1, $response->getCommandResponses());
        $this->assertInstanceOf(CommandResponse::class, $response->getCommandResponses()[0]);
        $this->assertSame(CommandResponse::STATUS_OK, $response->getCommandResponses()[0]->getStatus());
    }
}
