<?php

declare(strict_types=1);

namespace spec\Quote\Api\Domain\Model\Shout;

use PhpSpec\ObjectBehavior;
use Quote\Api\Domain\Model\Author\Author;
use Quote\Api\Domain\Model\Quote\Quote;

class ShoutSpec extends ObjectBehavior
{
    function it_is_a_shout_created_from_quote_finished_with_dot()
    {
        $author = Author::create('Steve Jobs');
        $quote = Quote::create($author, 'Great words.');

        $this->beConstructedWith($quote->toString());
        $this->toString()->shouldBe('GREAT WORDS!');
    }

    function it_is_a_shout_created_from_quote_finished_with_not_dot()
    {
        $author = Author::create('Steve Jobs');
        $quote = Quote::create($author, 'Great words');

        $this->beConstructedWith($quote->toString());
        $this->toString()->shouldBe('GREAT WORDS!');
    }
}
