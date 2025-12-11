<?php

namespace DisciteObfuscator\Config;

class IdsConfig
{
    /** 
     * Prefix for the obfuscated ID names
     *
     * @var string|null
     */
    public static ?string $prefix = 'i';

    /** 
     * Suffix for the obfuscated ID names
     *
     * @var string|null
     */
    public static ?string $suffix = null;

    /** 
     * List of CSS/Js IDs to exclude from obfuscation
     *
     * @var array
     */
    public static array $excludedIds = [
        // Add more IDs to exclude from obfuscation as needed
    ];
}

?>