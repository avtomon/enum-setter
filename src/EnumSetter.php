<?php

namespace Scaleplan\EnumSetter;

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
            $files = \scandir($directory, SCANDIR_SORT_NONE);
        } catch (\Throwable $e) {
            throw new DirectoryScanExceptions(DirectoryScanExceptions::MESSAGE . ' :' . $e->getMessage());
        }

        foreach ($files as $fileName) {
            if (stripos($fileName, '.php') === false) {
                continue;
            }

            $enum = $constNamespace . '\\' . str_ireplace('.php', '', $fileName);
            if (!defined($enum . '::' . static::ENUM_TYPE_NAME_CONST_NAME)
                && !defined($enum . '::' . static::ENUM_VALUES_CONST_NAME)
                && !interface_exists($enum)
                && class_exists($enum)
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
                $this->connection->exec("ALTER TYPE $enumName RENAME VALUE {$oldValues[$index]} TO $newValue");
                continue;
            }

            $this->connection->exec("ALTER TYPE $enumName ADD VALUE IF NOT EXISTS $newValue");
        }
    }
}
