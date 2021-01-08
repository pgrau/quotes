<?php

declare(strict_types=1);

namespace Quote\Api\Application\Query\GetShoutsByAuthor;

use Quote\Api\Domain\Model\Shout\ShoutCollection;

final class GetShoutsByAuthorQueryResponse
{
    public function __construct(private ShoutCollection $collection)
    {
    }

    public function response(): array
    {
        $shouts = [];
        foreach ( $this->collection->get() as $shout) {
            $shouts[] = $shout->toString();
        }


        return $shouts;
    }
}
