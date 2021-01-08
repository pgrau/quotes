<h1 align="center">
Quotes API  </br> Ports and Adapters, DDD, CQRS & <br/> Event Driven Architecture  in PHP
</h1>

<p align="left">
This project follow the Event Sourcing pattern described on <a href="https://docs.microsoft.com/en-us/azure/architecture/patterns/event-sourcing"/>Microsoft Site</a>.
</p>

<p align="center">
  <a href="https://docs.microsoft.com/en-us/azure/architecture/patterns/event-sourcing">
    <img src="https://docs.microsoft.com/en-us/azure/architecture/patterns/_images/event-sourcing-overview.png" width="640px" />
  </a>
</p>

### ✔ Project Technology
<p>This project contains the follow features</p>
<p>1. The Event Store is the authoritative data source. All events are stored using an append-only operation.</p>
<p>2. Subscribers build materialized views.</p>
<p>3. External systems and applications have available all domain events by message queue.</p>

### 🖥️ Stack Technology

<p>PHP 8</p>
<p>MySQL 8.0</p>
<p>RabbitMQ 3.8.9 - Erlang 23.2.1</p>
<p>Redis 6.0</p>

## 🚀 Environment Setup

### 🐳 Needed tools

1. [Install Docker](https://www.docker.com/get-started)
2. Clone this project: `git clone https://github.com/pgrau/quotes`
3. Move to the project folder: `cd quotes`

### 🔥 Application execution

1. Install and configure all the dependencies and bring up the project executing:
   `make dev`

## 👩‍💻 Project explanation

<p>REST API that given a famous person and a count N, returns N quotes from this famous person shouted.</p>

You can:

1. Verify the [api status](http://localhost:8082) `http://localhost:8082`
2. Import authors from json to mysql by cli `docker exec -it quotes-php bin/console api:import:authors`
2. Import quotes from json to mysql by cli `docker exec -it quotes-php bin/console api:import:quotes`
4. Get shouts by author. [Example Steve Jobs](http://localhost:8082/shout/steve-jobs)  `http://localhost:8082/shout/steve-jobs`

### 🎯 Ports and Adapters / Hexagonal Architecture

This repository follow the Ports and Adapters / Hexagonal Architecture  pattern.

```
src
├── Api
│ ├── Application
│ │ ├── Command
│ │ │ ├── ImportAuthors
│ │ │ │ ├── ImportAuthorsCommand.php
│ │ │ │ └── ImportAuthorsCommandHandler.php
│ │ │ └── ImportQuotes
│ │ │     ├── ImportQuotesCommand.php
│ │ │     └── ImportQuotesCommandHandler.php
│ │ └── Query
│ │     └── GetShoutsByAuthor
│ │         ├── GetShoutsByAuthorQuery.php
│ │         ├── GetShoutsByAuthorQueryHandler.php
│ │         └── GetShoutsByAuthorQueryResponse.php
│ ├── Domain
│ │ ├── Event
│ │ │ ├── Author
│ │ │ │ └── CreateAuthorSubscriber.php
│ │ │ ├── Quote
│ │ │ │ └── CreateQuoteSubscriber.php
│ │ │ └── Shout
│ │ │     └── ShoutsByAuthorSubscriber.php
│ │ └── Model
│ │     ├── Author
│ │     │ ├── Author.php
│ │     │ ├── AuthorCreatedV1.php
│ │     │ ├── AuthorNotFound.php
│ │     │ ├── AuthorProjection.php
│ │     │ └── AuthorRepository.php
│ │     ├── Quote
│ │     │ ├── Quote.php
│ │     │ ├── QuoteCreatedV1.php
│ │     │ ├── QuoteProjection.php
│ │     │ └── QuoteRepository.php
│ │     └── Shout
│ │         ├── Shout.php
│ │         ├── ShoutCollection.php
│ │         └── ShoutsByAuthorRequestedV1.php
│ └── Infrastructure
│     ├── Framework
│     │ └── Symfony
│     │     └── Kernel.php
│     ├── Persistence
│     │ ├── Dbal
│     │ │ ├── Author
│     │ │ │ └── DbalAuthorProjection.php
│     │ │ └── Quote
│     │ │     └── DbalQuoteProjection.php
│     │ └── Doctrine
│     │     ├── Author
│     │     │ └── DoctrineAuhorRepository.php
│     │     ├── Quote
│     │     │ └── DoctrineQuoteRepository.php
│     │     └── mapping
│     │         ├── Author.Author.orm.xml
│     │         └── Quote.Quote.orm.xml
│     └── UI
│         ├── Command
│         │ ├── Author
│         │ │ └── ImportAuthorsCommand.php
│         │ └── Quote
│         │     └── ImportQuotesCommand.php
│         └── Controller
│             ├── GetQuotesByAuthor
│             │ └── GetQuotesByAuthorController.php
│             └── Ping
│                 └── PingController.php
└── Shared
    ├── Domain
    │ └── Model
    │     ├── Aggregate
    │     │ └── AggregateRoot.php
    │     ├── Api
    │     │ └── ApiError.php
    │     ├── Bus
    │     │ ├── CommandBus.php
    │     │ ├── EventBus.php
    │     │ └── QueryBus.php
    │     ├── Cache
    │     │ └── CacheRepository.php
    │     ├── Command.php
    │     ├── Event
    │     │ ├── DomainEvent.php
    │     │ ├── DomainEventPublisher.php
    │     │ ├── DomainEventSubscriber.php
    │     │ ├── EventFailed.php
    │     │ ├── EventFailedNotFoundException.php
    │     │ ├── EventFailedStore.php
    │     │ ├── EventNotPublished.php
    │     │ ├── EventNotPublishedNotFoundException.php
    │     │ ├── EventNotPublishedStore.php
    │     │ ├── EventStore.php
    │     │ ├── PublishableDomainEvent.php
    │     │ ├── StoredEvent.php
    │     │ └── StoredEventNotFoundException.php
    │     ├── Exception
    │     │ ├── BadRequestException.php
    │     │ ├── ConflictException.php
    │     │ ├── NotFoundException.php
    │     │ └── ProjectException.php
    │     ├── Query.php
    │     └── Serializer
    │         └── Serializer.php
    └── Infrastructure
        ├── Bus
        │ ├── Custom
        │ │ └── CustomEventBus.php
        │ └── League
        │     ├── CommandBus.php
        │     ├── CommandBusFactory.php
        │     ├── CommandBusProvider.php
        │     ├── DbalTransactionMiddleware.php
        │     ├── QueryBus.php
        │     ├── QueryBusFactory.php
        │     └── QueryBusProvider.php
        ├── MessageBroker
        │ ├── Dispatcher.php
        │ ├── RabbitMq
        │ │ ├── RabbitMqConfigurer.php
        │ │ ├── RabbitMqConnection.php
        │ │ ├── RabbitMqDomainEventsConsumer.php
        │ │ ├── RabbitMqExchangeNameFormatter.php
        │ │ ├── RabbitMqFactoryConfigurer.php
        │ │ ├── RabbitMqFactoryConnection.php
        │ │ └── RabbitMqPublisher.php
        │ └── SubscriberMapper.php
        ├── Persistence
        │ ├── Dbal
        │ │ └── Event
        │ │     ├── DbalEventFailedStore.php
        │ │     ├── DbalEventNotPublishedStore.php
        │ │     └── DbalEventStore.php
        │ ├── FakeCacheRepository.php
        │ └── Redis
        │     └── Cache
        │         └── RedisCacheRepository.php
        ├── Serializer
        │ └── PhpSerializer.php
        └── UI
            └── Command
                └── MessageBroker
                    ├── RabbitMqConfigureCommand.php
                    ├── RabbitMqConsumeDomainEventsCommand.php
                    └── RabbitMqUpDomainEventsConsumers.php
``` 

### 🎯 Command Bus

We use command bus for all use cases need write

All commands are executed with transactional mode

[Example](src/Api/Infrastructure/UI/Command/Author/ImportAuthorsCommand.php):

```
     ...
     
     $command = new \Quote\Api\Application\Command\ImportAuthors\ImportAuthorsCommand($data);

     $this->commandBus->dispatch($command);

     ...
```

### Query Bus

We use query bus for all use cases need only read

All queries are executed without transaction

[Example](src/Api/Infrastructure/UI/Controller/GetShoutsByAuthor/GetShoutsByAuthorController.php):

```
     ...
     
     $query = new GetShoutsByAuthorQuery($authorId, (int) $limit);

     $response = $this->queryBus->ask($query)

     ...
```