<?php

namespace Lmc\Matej\Exception;

use Assert\AssertionFailedException;

/**
 * Exception thrown when invalid value is passed while creating domain model.
 *
 * @codeCoverageIgnore
 */
class DomainException extends LogicException implements AssertionFailedException
{
    /** @var string|null */
    private $propertyPath;
    /** @var mixed */
    private $value;
    /** @var array */
    private $constraints;

    /**
     * @param mixed $value
     * @param mixed $message
     * @param mixed $code
     * @param null|mixed $propertyPath
     * @param array $constraints
     */
    public function __construct($message, $code, $propertyPath, $value, array $constraints = [])
    {
        parent::__construct($message, $code);
        $this->propertyPath = $propertyPath;
        $this->value = $value;
        $this->constraints = $constraints;
    }

    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getConstraints()
    {
        return $this->constraints;
    }
}
