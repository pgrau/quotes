<?php

declare(strict_types=1);

namespace spec\Quote\Api\Domain\Model\Quote;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class QuoteCreatedV1Spec extends ObjectBehavior
{
    function it_is_a_new_domain_event()
    {
        $aggregateId = Uuid::uuid4()->toString();
        $quote = 'Be water my friend';
        $authorId = 'bruce-lee';

        $this->beConstructedWith($aggregateId, $quote, $authorId);
        $this->payload()['id']->shouldBe($aggregateId);
        $this->payload()['quote']->shouldBe($quote);
        $this->payload()['author_id']->shouldBe($authorId);
    }
}
