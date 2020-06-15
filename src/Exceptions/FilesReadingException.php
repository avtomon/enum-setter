<?php

namespace Scaleplan\EnumSetter\Exceptions;

/**
 * Class FilesReadingException
 *
 * @package Scaleplan\EnumSetter
 */
class FilesReadingException extends EnumSetterException
{
    public const MESSAGE = 'Ошибка чтения файла.';
    public const CODE = 523;
}
