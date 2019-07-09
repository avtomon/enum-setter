<?php

namespace Scaleplan\EnumSetter\Exceptions;

/**
 * Class FilesReadingException
 *
 * @package Scaleplan\EnumSetter
 */
class FilesReadingException extends EnumSetterException
{
    public const MESSAGE = 'Files reading error';
    public const CODE = 523;
}
