<?php

namespace Scaleplan\EnumSetter;

/**
 * Class DirectoryScanExceptions
 *
 * @package Scaleplan\EnumSetter
 */
class DirectoryScanExceptions extends EnumSetterException
{
    public const MESSAGE = 'Directory scan error';
    public const CODE = 523;
}
