<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\BroadcastAndListen\Traits;

use pointybeard\Helpers\Foundation\BroadcastAndListen\Exceptions;

trait HasBroadcasterTrait
{
    public function broadcast(string $message, ...$arguments): void
    {
        // There are no listeners
        if (false == $this->hasListeners()) {
            return;
        }

        // Include the initiator in the arguments sent. Note that
        // modifying this object in a listener has no effect outside
        // of that listener's scope.
        array_unshift($arguments, $this);

        foreach ($this->listeners() as $position => $func) {
            // Silently reject invalid listener callbacks. This should never
            // happen though since the addListener() method only accepts a
            // Callable. Given this is a trait, however, it is possible for
            // something else to mess around with $listeners.
            if (false == is_callable($func)) {
                throw new Exceptions\InvalidListenerCallbackException($position);
            }
            $func($message, ...$arguments);
        }
    }
}
