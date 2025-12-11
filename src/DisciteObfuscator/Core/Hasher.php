<?php

namespace DisciteObfuscator\Core;

use DisciteObfuscator\Config\AttributesConfig;
use DisciteObfuscator\Config\ClassesConfig;
use DisciteObfuscator\Config\IdsConfig;
use DisciteObfuscator\Config\VarsConfig;

class Hasher
{
    /**
     * Counter for generating unique obfuscated names
     *
     * @var int
     */
    private int $counter = 0;

    /**
     * Format the next obfuscated class name
     *
     * @return string
     */
    public function formatClass(): string
    {
        return ClassesConfig::$prefix . base_convert($this->counter++, 10, 36) . ClassesConfig::$suffix;
    }

    /**
     * Format the next obfuscated ID name
     *
     * @return string
     */
    public function formatId(): string
    {
        return IdsConfig::$prefix . base_convert($this->counter++, 10, 36) . IdsConfig::$suffix;
    }

    /**
     * Format the next obfuscated variable name
     *
     * @return string
     */
    public function formatVar(): string
    {
        return VarsConfig::$prefix . base_convert($this->counter++, 10, 36) . VarsConfig::$suffix;
    }

    /**
     * Format the next obfuscated attribute name
     *
     * @return string
     */
    public function formatAttribute(): string
    {
        return AttributesConfig::$prefix . base_convert($this->counter++, 10, 36) . AttributesConfig::$suffix;
    }
}

?>