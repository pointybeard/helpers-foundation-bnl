<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\BroadcastAndListen\Exceptions;

use pointybeard\Helpers\Exceptions\ReadableTrace;

class BroadcastAndListenExceptionException extends ReadableTrace\ReadableTraceException
{
    public function getReadableTrace(string $format = '[{{PATH}}/{{FILENAME}}:{{LINE}}] {{CLASS}}{{TYPE}}{{FUNCTION}}();'): ?string
    {
        // The trace of any previous exception that is an instance of
        // ReadableTraceException is likely to be much more informative
        // so use that intead
        if ($this->getPrevious() instanceof \pointybeard\Helpers\Exceptions\ReadableTrace\ReadableTraceException) {
            return $this->getPrevious()->getReadableTrace();
        }

        // Otherwise, default to whatever trace we have
        return parent::getReadableTrace();
    }
}
