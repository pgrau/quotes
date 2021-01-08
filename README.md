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
<p>This project have the follow features</p>
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