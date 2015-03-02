<?php

namespace SimpleBus\RabbitMQBundle\Tests;

use SimpleBus\RabbitMQBundle\RabbitMQPublisher;

class RabbitMQPublisherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_serializes_the_message_and_publishes_it()
    {
        $message = $this->dummyMessage();
        $serializedMessageEnvelope = 'the-serialized-message-envelope';
        $serializer = $this->mockSerializer();
        $serializer
            ->expects($this->once())
            ->method('wrapAndSerialize')
            ->with($message)
            ->will($this->returnValue($serializedMessageEnvelope));

        $producer = $this->mockProducer();
        $producer
            ->expects($this->once())
            ->method('publish')
            ->with($this->identicalTo($serializedMessageEnvelope));

        $publisher = new RabbitMQPublisher($serializer, $producer);

        $publisher->publish($message);
    }

    private function mockSerializer()
    {
        return $this->getMock('SimpleBus\Asynchronous\Message\Envelope\Serializer\MessageInEnvelopSerializer');
    }

    private function mockProducer()
    {
        return $this
            ->getMockBuilder('OldSound\RabbitMqBundle\RabbitMq\Producer')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }
}