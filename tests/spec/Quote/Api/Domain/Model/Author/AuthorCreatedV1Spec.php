<?php

declare(strict_types=1);

namespace spec\Quote\Api\Domain\Model\Author;

use PhpSpec\ObjectBehavior;
use Quote\Api\Domain\Model\Author\AuthorCreatedV1;
use Quote\Shared\Domain\Model\Event\DomainEvent;
use Ramsey\Uuid\Uuid;

class AuthorCreatedV1Spec extends ObjectBehavior
{
    function it_is_a_new_domain_event()
    {
        $aggregateId = Uuid::uuid4()->toString();
        $fullName = 'Steve Jobs';

        $this->beConstructedWith($aggregateId, $fullName);
        $this->payload()['id']->shouldBe($aggregateId);
        $this->payload()['full_name']->shouldBe($fullName);
        $this->priority()->shouldBe(DomainEvent::PRIORITY_LOW);
    }
}
