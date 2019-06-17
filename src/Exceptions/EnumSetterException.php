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
    public const CODE = 500;

    /**
     * EnumSetterException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?: static::MESSAGE, $code ?: static::CODE, $previous);
    }
}
