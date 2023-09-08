<?php

namespace Elsayed85\RedisService\Commands;

use Illuminate\Console\Command;

class RedisServiceCommand extends Command
{
    public $signature = 'redis-service';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
