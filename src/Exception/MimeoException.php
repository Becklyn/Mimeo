<?php declare(strict_types=1);

namespace Becklyn\Mimeo\Exception;

class MimeoException extends \RuntimeException
{
    /**
     * @inheritDoc
     */
    public function __construct (string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
