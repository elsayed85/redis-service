<?php

namespace Elsayed85\RedisService\Events;

abstract class Event
{
    public function toJson(): string
    {
        return json_encode($this);
    }
}
