<?php

namespace Scaleplan\EnumSetter\Exceptions;

use function Scaleplan\Translator\translate;

/**
 * Class EnumSetterException
 *
 * @package Scaleplan\EnumSetter
 */
class EnumSetterException extends \Exception
{
    public const MESSAGE = 'enum-setter.enum-set-error';
    public const CODE = 500;

    /**
     * EnumSetterException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     *
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            $message ?: translate(static::MESSAGE) ?: static::MESSAGE,
            $code ?: static::CODE,
            $previous
        );
    }
}
