<?php

namespace Scaleplan\EnumSetter;

use Scaleplan\EnumSetter\Exceptions\FilesReadingException;

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
     * @param string $directoryOrFile
     * @param string $constNamespace
     *
     * @throws FilesReadingException
     */
    public function applyTypes(string $directoryOrFile, string $constNamespace) : void
    {
        try {
            if (\is_dir($directoryOrFile)) {
                $files = \scandir($directoryOrFile, SCANDIR_SORT_NONE);
            } else {
                $files = [$directoryOrFile];
            }
        } catch (\Throwable $e) {
            throw new FilesReadingException(FilesReadingException::MESSAGE . ': ' . $e->getMessage());
        }

        foreach ($files as $fileName) {
            if (stripos($fileName, '.php') === false) {
                continue;
            }

            $enum = "\\$constNamespace\\" . str_ireplace('.php', '', $fileName);
            if (!defined($enum . '::' . static::ENUM_TYPE_NAME_CONST_NAME)
                && !defined($enum . '::' . static::ENUM_VALUES_CONST_NAME)
                && !interface_exists($enum)
                && class_exists($enum)
            ) {
                continue;
            }

            $all = \constant($enum . '::' . static::ENUM_VALUES_CONST_NAME);
            if (!\is_array($all) && !count($all)) {
                continue;
            }

            $this->applyType($all, \constant($enum . '::' . static::ENUM_TYPE_NAME_CONST_NAME));
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
            ->query("SELECT to_json(enum_range(NULL::$enumName)) AS enum")
            ->fetchAll();

        $oldValues = \json_decode($oldValues[0]['enum'], false);
        foreach (array_diff($newValues, $oldValues) as $index => $newValue) {
            $this->connection->exec("ALTER TYPE $enumName ADD VALUE IF NOT EXISTS '$newValue'");
        }
    }
}
