<?php

namespace DisciteObfuscator\Config;

class AttributesConfig
{
    /** 
     * Prefix for the obfuscated attribute names
     *
     * @var string|null
     */
    public static ?string $prefix = 'a';

    /** 
     * Suffix for the obfuscated variable names
     *
     * @var string|null
     */
    public static ?string $suffix = null;

    /**
     * List of CSS/Js attributes to exclude from obfuscation
     *
     * @var array
     */
    public static array $excludedAttributes = [
        'src',
        'href',
        'alt',
        'title',
        'rel',
        'integrity',
        'crossorigin',
        'referrerpolicy',
        'type',
    ];
}

?>