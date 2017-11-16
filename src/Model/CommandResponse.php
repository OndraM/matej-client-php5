<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\InvalidDomainModelArgumentException;

/**
 * Response to one single command which was part of request batch.
 */
class CommandResponse
{
    const STATUS_OK = 'OK';
    const STATUS_ERROR = 'ERROR';
    const STATUS_SKIPPED = 'SKIPPED';
    /** @var string */
    private $status;
    /** @var string */
    private $message;
    /** @var array */
    private $data = [];

    private function __construct()
    {
    }

    public static function createFromRawCommandResponseObject(\stdClass $rawCommandResponseObject)
    {
        if (!isset($rawCommandResponseObject->status)) {
            throw new InvalidDomainModelArgumentException('Status field is missing in command response object');
        }
        $commandResponse = new self();
        $commandResponse->status = $rawCommandResponseObject->status;
        $commandResponse->message = isset($rawCommandResponseObject->message) ? $rawCommandResponseObject->message : '';
        $commandResponse->data = isset($rawCommandResponseObject->data) ? $rawCommandResponseObject->data : [];

        return $commandResponse;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getData()
    {
        return $this->data;
    }
}
