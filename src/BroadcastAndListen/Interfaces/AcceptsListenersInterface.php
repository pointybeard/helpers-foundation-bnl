<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\BroadcastAndListen\Interfaces;

interface AcceptsListenersInterface
{
    /**
     * Calling broadcast will send the payload to all listeners.
     */
    public function broadcast(string $message, ...$arguments): void;

    /**
     * The addListener method is how subscribers are added.
     */
    public function addListener(callable $callback): object;

    /**
     * The hasListeners method return true if there is at least on listener
     * subscribed to this object.
     */
    public function hasListeners(): bool;

    /**
     * The listerners method returns and iterator containing all the listeners.
     */
    public function listeners(): \ArrayIterator;
}
