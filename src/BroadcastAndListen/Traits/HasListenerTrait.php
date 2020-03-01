<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\BroadcastAndListen\Traits;

trait HasListenerTrait
{
    protected $listeners;

    public function addListener(callable $callback): object
    {
        if (false == ($this->listeners instanceof \ArrayIterator)) {
            $this->listeners = new \ArrayIterator();
        }
        $this->listeners->append($callback);

        return $this;
    }

    public function hasListeners(): bool
    {
        return $this->listeners()->count() > 0;
    }

    public function listeners(): \ArrayIterator
    {
        return $this->listeners;
    }
}
