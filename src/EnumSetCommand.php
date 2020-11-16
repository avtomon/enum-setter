<?php

namespace Scaleplan\EnumSetter;

use Scaleplan\Console\AbstractCommand;
use Scaleplan\EnumSetter\Exceptions\FilesReadingException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class EnumSetCommand
 *
 * @package Scaleplan\EnumSetter
 */
class EnumSetCommand extends AbstractCommand
{
    public const SIGNATURE = 'enums:set directory namespace';

    /**
     * @var EnumSetter
     */
    protected $enumSetter;

    /**
     * EnumSetCommand constructor.
     *
     * @param array $arguments
     *
     * @throws \ReflectionException
     * @throws \Scaleplan\Console\Exceptions\CommandSignatureIsEmptyException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $config = Yaml::parseFile(dirname(__DIR__) . '/config.yml');
        $connectionStructure = new ConnectionStructure();
        $connectionStructure->setDns($config['dns'] ?? null);
        $connectionStructure->setUser($config['user'] ?? null);
        $connectionStructure->setPassword($config['password'] ?? null);
        $connectionStructure->setDefaultSchema($config['default_schema'] ?? null);

        $this->enumSetter = new EnumSetter($connectionStructure);
    }

    /**
     * @throws FilesReadingException
     * @throws \Scaleplan\Console\Exceptions\CommandArgumentNotDefined
     */
    public function run() : void
    {
        $this->enumSetter->applyTypes($this->getArgument('directory'), $this->getArgument('namespace'));
    }
}
