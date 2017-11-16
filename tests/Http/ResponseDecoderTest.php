<?php

namespace Lmc\Matej\Http;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;
use Lmc\Matej\Exception\ResponseDecodingException;
use Lmc\Matej\Model\CommandResponse;
use PHPUnit\Framework\TestCase;

class ResponseDecoderTest extends TestCase
{
    /** @var ResponseDecoder */
    protected $decoder;

    /** @before */
    public function init()
    {
        $this->decoder = new ResponseDecoder();
    }

    /** @test */
    public function shouldDecodeSimpleOkResponse()
    {
        $response = $this->createJsonResponseFromFile(__DIR__ . '/Fixtures/response-one-successful-command.json');
        $output = $this->decoder->decode($response);
        $this->assertSame(1, $output->getNumberOfCommands());
        $this->assertSame(1, $output->getNumberOfSuccessfulCommands());
        $this->assertSame(0, $output->getNumberOfFailedCommands());
        $commandResponses = $output->getCommandResponses();
        $this->assertCount(1, $commandResponses);
        $this->assertInstanceOf(CommandResponse::class, $commandResponses[0]);
        $this->assertSame(CommandResponse::STATUS_OK, $commandResponses[0]->getStatus());
    }

    /** @test */
    public function shouldDecodeResponseMultipleResponses()
    {
        $response = $this->createJsonResponseFromFile(__DIR__ . '/Fixtures/response-item-properties.json');
        $output = $this->decoder->decode($response);
        $this->assertSame(3, $output->getNumberOfCommands());
        $this->assertSame(2, $output->getNumberOfSuccessfulCommands());
        $this->assertSame(1, $output->getNumberOfFailedCommands());
        $commandResponses = $output->getCommandResponses();
        $this->assertCount(3, $commandResponses);
        $this->assertContainsOnlyInstancesOf(CommandResponse::class, $commandResponses);
        $this->assertSame(CommandResponse::STATUS_OK, $commandResponses[0]->getStatus());
        $this->assertSame(CommandResponse::STATUS_ERROR, $commandResponses[1]->getStatus());
        $this->assertSame(CommandResponse::STATUS_OK, $commandResponses[2]->getStatus());
    }

    /** @test */
    public function shouldThrowExceptionWhenDecodingFails()
    {
        $notJsonData = file_get_contents(__DIR__ . '/Fixtures/invalid-json.html');
        $response = new Response(StatusCodeInterface::STATUS_NOT_FOUND, [], $notJsonData);
        $this->expectException(ResponseDecodingException::class);
        $this->expectExceptionMessage('Error decoding Matej response');
        $this->expectExceptionMessage('Status code: 404 Not Found');
        $this->expectExceptionMessage('<p>The requested URL /foo was not found on this server.</p>');
        $this->decoder->decode($response);
    }

    private function createJsonResponseFromFile($fileName)
    {
        $jsonData = file_get_contents($fileName);
        $response = new Response(StatusCodeInterface::STATUS_OK, ['Content-Type' => 'application/json'], $jsonData);

        return $response;
    }
}
