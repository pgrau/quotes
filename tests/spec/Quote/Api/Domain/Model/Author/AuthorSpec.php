<?php

declare(strict_types=1);

namespace spec\Quote\Api\Domain\Model\Author;

use PhpSpec\ObjectBehavior;
use Quote\Api\Domain\Model\Author\Author;

class AuthorSpec extends ObjectBehavior
{
    function it_is_a_new_instance()
    {
        $this->beConstructedWith('steve-jobs', 'Steve Jobs');
        $this->id()->shouldBe('steve-jobs');
        $this->fullName()->shouldBe('Steve Jobs');
    }

    function it_is_a_new_author()
    {
        $author = Author::create('Steve Jobs');

        $this->beConstructedWith($author->id(), $author->fullName());
        $this->id()->shouldBe('steve-jobs');
        $this->fullName()->shouldBe('Steve Jobs');
    }
}
