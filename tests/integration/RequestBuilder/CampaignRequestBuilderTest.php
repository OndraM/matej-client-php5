<?php

namespace Lmc\Matej\IntegrationTests\RequestBuilder;

use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\IntegrationTests\IntegrationTestCase;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserRecommendation;

/**
 * @covers \Lmc\Matej\RequestBuilder\CampaignRequestBuilder
 */
class CampaignRequestBuilderTest extends IntegrationTestCase
{
    /** @test */
    public function shouldThrowExceptionWhenSendingBlankRequest()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('At least one command must be added to the builder before sending the request');
        static::createMatejInstance()->request()->campaign()->send();
    }

    /** @test */
    public function shouldExecuteRecommendationAndSortingCommands()
    {
        $response = static::createMatejInstance()->request()->campaign()->addRecommendation($this->createRecommendationCommand('a'))->addRecommendations([$this->createRecommendationCommand('b'), $this->createRecommendationCommand('c')])->addSorting($this->createSortingCommand('a'))->addSortings([$this->createSortingCommand('b'), $this->createSortingCommand('c')])->send();
        $this->assertResponseCommandStatuses($response, ...$this->generateOkStatuses(6));
    }

    private function createRecommendationCommand($letter)
    {
        return UserRecommendation::create('user-' . $letter, 1, 'integration-test-scenario', 1, 3600);
    }

    private function createSortingCommand($letter)
    {
        return Sorting::create('user-' . $letter, ['itemA', 'itemB', 'itemC']);
    }
}
