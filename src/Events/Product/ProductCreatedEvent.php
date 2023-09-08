<?php

namespace Elsayed85\RedisService\Events\Product;

use Elsayed85\RedisService\Enums\Events;
use Elsayed85\RedisService\Events\Event;

class ProductCreatedEvent extends Event
{
    public Events $type = Events::PRODUCT_CREATED;
}
