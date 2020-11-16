<?php

namespace Scaleplan\EnumSetter\Exceptions;

/**
 * Class FilesReadingException
 *
 * @package Scaleplan\EnumSetter
 */
class FilesReadingException extends EnumSetterException
{
    public const MESSAGE = 'enum-setter.file-read-error';
    public const CODE = 523;
}
