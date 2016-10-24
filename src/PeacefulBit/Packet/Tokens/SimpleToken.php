<?php

namespace PeacefulBit\Packet\Tokens;

abstract class SimpleToken implements Token
{
    /**
     * @return string
     */
    public function __toString()
    {
        $name = explode('\\', static::class);
        return end($name);
    }
}
