<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\AMQP\Envelope;

abstract class DomainCommand implements Envelope
{
    private array $metadata = [];

    public function setMetaData(array $metadata): void
    {
        $this->metadata = array_merge($this->metadata, $metadata);
    }
}