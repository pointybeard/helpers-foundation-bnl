<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\BroadcastAndListen\Traits;

trait HasListenerTrait
{
    protected $listeners;

    protected function initListenersIterator() {
        if (false == ($this->listeners instanceof \ArrayIterator)) {
            $this->listeners = new \ArrayIterator();
        }
    }

    public function addListener(callable $callback): object
    {
        $this->initListenersIterator();
        $this->listeners->append($callback);

        return $this;
    }

    public function hasListeners(): bool
    {
        $this->initListenersIterator();
        return $this->listeners()->count() > 0;
    }

    public function listeners(): \ArrayIterator
    {
        $this->initListenersIterator();
        return $this->listeners;
    }
}
