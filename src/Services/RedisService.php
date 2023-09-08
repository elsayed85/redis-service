<?php

namespace Elsayed85\RedisService\Services;

use Carbon\Carbon;
use Elsayed85\RedisService\Events\Event;
use Illuminate\Support\Facades\Redis;

abstract class RedisService
{
    public const ALL_EVENTS_KEY = 'events';

    public const PROCESSED_EVENTS_KEY = 'processed_events';

    abstract public function getServiceName(): string;

    private function getProcessedEventKey()
    {
        return $this->getServiceName().'-'.self::PROCESSED_EVENTS_KEY;
    }

    public function publish(Event $event): void
    {
        Redis::xadd(self::ALL_EVENTS_KEY, '*', [
            'event' => $event->toJson(),
            'service' => $this->getServiceName(),
            'created_at' => Carbon::now()->valueOf(),
        ]);
    }

    public function addProcessedEvent(array $event): void
    {
        Redis::rpush(
            $this->getProcessedEventKey(),
            $event['id']
        );
    }

    public function getUnProcessedEvenets(): array
    {
        $lastProcessedEventId = $this->getLastProcessedEventId(); // [timestamp]

        $events = $this->getEventsAfter($lastProcessedEventId);

        return $this->parseEvents($events);
    }

    private function getLastProcessedEventId(): string
    {
        $lastId = Redis::lindex($this->getProcessedEventKey(), -1);

        if (empty($lastId)) {
            return (string) Carbon::subyears(10)->valueOf(); // return all events if no processed events found
        }

        return $lastId;
    }

    protected function getEventsAfter(string $start): array
    {
        $events = Redis::xRange(
            self::ALL_EVENTS_KEY,
            $start,
            (int) Carbon::now()->valueOf()
        );

        if (! $events) {
            return [];
        }

        unset($events[$start]); // remove start because it's already processed

        return $events;
    }

    protected function parseEvents(array $redisEvents): array
    {
        return collect($redisEvents)
            ->map(function (array $item, string $id) {
                return array_merge(
                    json_decode($item['event'], true),
                    ['id' => $id]
                );
            })
            ->all();
    }
}
