<?php

namespace Scaleplan\EnumSetter;

/**
 * Class EnumSetterException
 *
 * @package Scaleplan\EnumSetter
 */
class EnumSetterException extends \Exception
{
    public const MESSAGE = 'Enum setter error.';

    /**
     * EnumSetterException constructor.
     *
     * @param string|null $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = null, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?? static::MESSAGE, $code, $previous);
    }
}
