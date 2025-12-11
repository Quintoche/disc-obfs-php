<?php

namespace DisciteObfuscator\Config;

class ClassesConfig
{
    /** 
     * Prefix for the obfuscated class names
     *
     * @var string|null
     */
    public static ?string $prefix = 'c';

    /** 
     * Suffix for the obfuscated class names
     *
     * @var string|null
     */
    public static ?string $suffix = null;

    /** 
     * List of CSS/Js classes to exclude from obfuscation
     *
     * @var array
     */
    public static array $excludedClasses = [
        // Add more classes to exclude from obfuscation as needed
    ];
}

?>