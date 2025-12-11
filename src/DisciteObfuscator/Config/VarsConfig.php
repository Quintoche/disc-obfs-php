<?php

namespace DisciteObfuscator\Config;

class VarsConfig
{
    /** 
     * Prefix for the obfuscated variable names
     *
     * @var string|null
     */
    public static ?string $prefix = '--v';

    /** 
     * Suffix for the obfuscated variable names
     *
     * @var string|null
     */
    public static ?string $suffix = null;

    /** 
     * List of CSS/Js variables to exclude from obfuscation
     *
     * @var array
     */
    public static array $excludedVariables = [
        // Add more variables to exclude from obfuscation as needed
    ];
}

?>