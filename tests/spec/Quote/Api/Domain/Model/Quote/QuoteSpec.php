<?php

declare(strict_types=1);

namespace spec\Quote\Api\Domain\Model\Quote;

use PhpSpec\ObjectBehavior;
use Quote\Api\Domain\Model\Author\Author;
use Quote\Api\Domain\Model\Quote\Quote;
use Ramsey\Uuid\Uuid;

class QuoteSpec extends ObjectBehavior
{
    function it_is_a_new_instance()
    {
        $id = Uuid::uuid4();
        $author = Author::create('Steve Jobs');
        $quote = 'Be water my friend';

        $this->beConstructedWith($id, $author, $quote);
        $this->id()->toString()->shouldBe($id->toString());
        $this->author()->id()->shouldBe($author->id());
        $this->quote()->shouldBe($quote);
        $this->toString()->shouldBe($quote);
    }

    function it_is_a_new_quote()
    {
        $quote = 'Be water my friend';
        $author = Author::create('Steve Jobs');
        $quoteAggregate  = Quote::create($author, $quote);

        $this->beConstructedWith($quoteAggregate->id(), $quoteAggregate->author(), $quoteAggregate->toString());
        $this->id()->toString()->shouldBe($quoteAggregate->id()->toString());
        $this->author()->id()->shouldBe($quoteAggregate->author()->id());
        $this->quote()->shouldBe($quoteAggregate->quote());
        $this->toString()->shouldBe($quoteAggregate->quote());
    }
}
