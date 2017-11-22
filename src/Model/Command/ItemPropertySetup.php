<?php

namespace Lmc\Matej\Model\Command;

/**
 * Command to add or delete item property in the database.
 */
class ItemPropertySetup extends AbstractCommand
{
    const PROPERTY_TYPE_INT = 'int';
    const PROPERTY_TYPE_DOUBLE = 'double';
    const PROPERTY_TYPE_STRING = 'string';
    const PROPERTY_TYPE_BOOLEAN = 'boolean';
    const PROPERTY_TYPE_TIMESTAMP = 'timestamp';
    const PROPERTY_TYPE_SET = 'set';
    /** @var string */
    private $propertyName;
    /** @var string */
    private $propertyType;

    private function __construct($propertyName, $propertyType)
    {
        // TODO: assert propertyName format
        // TODO: assert propertyType is one of PROPERTY_TYPE_*
        $this->propertyName = $propertyName;
        $this->propertyType = $propertyType;
    }

    public static function int($propertyName)
    {
        return new static($propertyName, self::PROPERTY_TYPE_INT);
    }

    public static function double($propertyName)
    {
        return new static($propertyName, self::PROPERTY_TYPE_DOUBLE);
    }

    public static function string($propertyName)
    {
        return new static($propertyName, self::PROPERTY_TYPE_STRING);
    }

    public static function boolean($propertyName)
    {
        return new static($propertyName, self::PROPERTY_TYPE_BOOLEAN);
    }

    public static function timestamp($propertyName)
    {
        return new static($propertyName, self::PROPERTY_TYPE_TIMESTAMP);
    }

    public static function set($propertyName)
    {
        return new static($propertyName, self::PROPERTY_TYPE_SET);
    }

    protected function getCommandType()
    {
        return 'item-properties-setup';
    }

    protected function getCommandParameters()
    {
        return ['property_name' => $this->propertyName, 'property_type' => $this->propertyType];
    }
}
