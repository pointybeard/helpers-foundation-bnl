<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\BroadcastAndListen\Exceptions;

final class InvalidListenerCallbackException extends BroadcastAndListenExceptionException
{
    public function __construct(int $position, $code = 0, \Exception $previous = null)
    {
        parent::__construct("Invalid callback at position {$position} of listener iterator.", $code, $previous);
    }
}
