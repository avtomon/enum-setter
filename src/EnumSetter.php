<?php

namespace Scaleplan\EnumSetter;

use HaydenPierce\ClassFinder\ClassFinder;

/**
 * Class EnumSetter
 *
 * @package Scaleplan\EnumSetter
 */
class EnumSetter
{
    public const ENUM_TYPE_NAME_CONST_NAME = 'enum';
    public const ENUM_VALUES_CONST_NAME = 'ALL';

    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * @var string
     */
    protected $defaultSchema;

    /**
     * EnumSetter constructor.
     *
     * @param ConnectionStructure $connectionStructure
     */
    public function __construct(ConnectionStructure $connectionStructure)
    {
        $this->connection = new \PDO(
            $connectionStructure->getDns(),
            $connectionStructure->getUser(),
            $connectionStructure->getPassword()
        );

        $this->defaultSchema = $connectionStructure->getDefaultSchema();
    }

    /**
     * @param string $directory
     * @param string $constNamespace
     *
     * @throws DirectoryScanExceptions
     */
    public function applyTypes(string $directory, string $constNamespace) : void
    {
        try {
            $files = \scandir($directory);
        } catch (\Throwable $e) {
            throw new DirectoryScanExceptions(DirectoryScanExceptions::MESSAGE . ' :' . $e->getMessage());
        }

        foreach ($files as $fileName) {
            if (stripos($fileName, '.php') === false) {
                continue;
            }

            $enum = $constNamespace . '\\' . str_ireplace('.php', '', $fileName);
            if (!interface_exists($enum)
                && class_exists($enum)
                && !defined($enum . '::' . static::ENUM_TYPE_NAME_CONST_NAME)
                && !defined($enum . '::' . static::ENUM_VALUES_CONST_NAME)
            ) {
                continue;
            }

            $all = $enum::{static::ENUM_VALUES_CONST_NAME};
            if (!\is_array($all) && !count($all)) {
                continue;
            }

            $this->applyType($all, $enum);
        }
    }

    /**
     * @param array $newValues
     * @param string $enumName
     */
    protected function applyType(array $newValues, string $enumName) : void
    {
        if (strpos($enumName, '.') === false) {
            $enumName = "{$this->defaultSchema}.$enumName";
        }

        $oldValues = $this->connection
            ->query("SELECT unnest(enum_range(NULL::$enumName))")
            ->fetchAll(\PDO::FETCH_OBJ);

        foreach ($newValues as $index => $newValue) {
            if (isset($oldValues[$index])) {
                $statement = $this->connection->prepare(
                    "ALTER TYPE $enumName RENAME VALUE :old_value TO :new_value"
                );
                $statement->execute(['old_value' => $oldValues[$index], 'new_value' => $newValue]);
                continue;
            }

            $statement = $this->connection->prepare(
                "ALTER TYPE $enumName ADD VALUE IF NOT EXISTS :new_value"
            );
            $statement->execute(['new_value' => $newValue]);
        }
    }
}