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

<p>PHP 8 - Symfony 5.2</p>
<p>MySQL 8.0</p>
<p>RabbitMQ 3.8 - Erlang 23.2</p>
<p>Redis 6.0</p>

## 🚀 Environment Setup

### 🐳 Needed tools

1. [Install Docker](https://www.docker.com/get-started)
2. Clone this project: `git clone https://github.com/pgrau/quotes`
3. Move to the project folder: `cd quotes`

### 🔥 Application execution

1. Install and configure all the dependencies and bring up the project executing:
   `make dev`
2. Execute the tests executing:
   `make test`

## 👩‍💻 Project explanation

<p>REST API that given a famous person and a count N, returns N quotes from this famous person shouted.</p>

You can:

1. Verify the [api status](http://localhost:8082) `http://localhost:8082`
2. Import authors from json to mysql by cli `docker exec -it quotes-php bin/console api:import:authors`
2. Import quotes from json to mysql by cli `docker exec -it quotes-php bin/console api:import:quotes`
4. Get shouts by author. [Example Steve Jobs](http://localhost:8082/shout/steve-jobs)  `http://localhost:8082/shout/steve-jobs`

## 👩‍💻 Strategy and Plan Execution

[Strategy and Plan Execution](https://github.com/pgrau/quotes/wiki/Execution-Project)

### 🎯 API Documentation

[Contracts](https://github.com/pgrau/quotes/wiki/Contract)

[OpenAPI Specification](https://github.com/pgrau/quotes/wiki/OpenAPI)

[Postman Collection](https://github.com/pgrau/quotes/wiki/Postman)

### 🎯 Ports and Adapters / Hexagonal Architecture

[Wiki Page](https://github.com/pgrau/quotes/wiki/Hexagonal-Architecture)

### 🎯 Command Bus

[Wiki Page](https://github.com/pgrau/quotes/wiki/Command-Bus)

### 🎯 Query Bus

[Wiki Page](https://github.com/pgrau/quotes/wiki/Query-Bus)

### 🎯 Event Sourcing

All documentation available on the Wiki:

[Event sourcing architecture](https://github.com/pgrau/quotes/wiki/Event-Sourcing-Architecture)    
    
[Domain Events](https://github.com/pgrau/quotes/wiki/Domain-Events)
    
[Event Store](https://github.com/pgrau/quotes/wiki/Event-Store)

[Event Bus](https://github.com/pgrau/quotes/wiki/Event-Bus)

[Message Broker](https://github.com/pgrau/quotes/wiki/Message-Broker)

[Consumer](https://github.com/pgrau/quotes/wiki/Consumer)

[Event Dispatcher](https://github.com/pgrau/quotes/wiki/Consumer)

[Subscriber](https://github.com/pgrau/quotes/wiki/Subscriber)

[Installation](https://github.com/pgrau/quotes/wiki/Installation)

###  🔦 Unit / Integration / Functional Test

[Phpspec](https://github.com/pgrau/quotes/wiki/phpspec)

[Behat](https://github.com/pgrau/quotes/wiki/phpspec)

