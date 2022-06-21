<?php

namespace Lankhaar\Multilingual\Exception;

class InvalidConfigException extends \Exception
{
    /**
     * Static helper to easily build a message for incorrect types
     *
     * @param string $config
     * @param string $expectedType
     * @param string $receivedType
     *
     * @return InvalidConfigException
     */
    public static function invalidType(string $config, string $expectedType, string $receivedType): InvalidConfigException
    {
        $message = sprintf('Config value of %s should be of type %s, %s received', ...func_get_args());
        return new static($message);
    }
}
