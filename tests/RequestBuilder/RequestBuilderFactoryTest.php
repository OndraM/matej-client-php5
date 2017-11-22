<?php

namespace Lmc\Matej\RequestBuilder;

use Lmc\Matej\Http\RequestManager;
use Lmc\Matej\Model\Command\ItemProperty;
use Lmc\Matej\Model\Command\ItemPropertySetup;
use Lmc\Matej\Model\Request;
use Lmc\Matej\Model\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lmc\Matej\RequestBuilder\RequestBuilderFactory
 */
class RequestBuilderFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideBuilderMethods
     * @param mixed $factoryMethod
     * @param mixed $expectedBuilderClass
     * @param \Closure $minimalBuilderInit
     */
    public function shouldInstantiateBuilderToBuildAndSendRequest($factoryMethod, $expectedBuilderClass, \Closure $minimalBuilderInit)
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())->method('sendRequest')->with($this->isInstanceOf(Request::class))->willReturn(new Response(0, 0, 0, 0));
        $factory = new RequestBuilderFactory($requestManagerMock);
        /** @var AbstractRequestBuilder $builder */
        $builder = $factory->{$factoryMethod}();
        // Builders may require some minimal setup to be able to execute the build() method
        $minimalBuilderInit($builder);
        $this->assertInstanceOf($expectedBuilderClass, $builder);
        $this->assertInstanceOf(Request::class, $builder->build());
        // Make sure the builder has been properly configured and it can execute send() via RequestManager mock:
        $this->assertInstanceOf(Response::class, $builder->send());
    }

    /**
     * @return array[]
     */
    public function provideBuilderMethods()
    {
        $itemPropertiesSetupInit = function (ItemPropertiesSetupRequestBuilder $builder) {
            $builder->addProperty(ItemPropertySetup::timestamp('valid_from'));
        };
        $eventInit = function (EventsRequestBuilder $builder) {
            $builder->addItemProperty(ItemProperty::create('item-id', []));
        };

        return [['setupItemProperties', ItemPropertiesSetupRequestBuilder::class, $itemPropertiesSetupInit], ['deleteItemProperties', ItemPropertiesSetupRequestBuilder::class, $itemPropertiesSetupInit], ['events', EventsRequestBuilder::class, $eventInit]];
    }
}
