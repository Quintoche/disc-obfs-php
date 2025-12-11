<?php

namespace DisciteObfuscator;

use DisciteObfuscator\Core\Extractor;
use DisciteObfuscator\Core\Hasher;
use DisciteObfuscator\Core\Replacer;
use DisciteObfuscator\Core\Saver;

class DisciteObfuscator
{
    private DisciteMap $styleMap;

    private Hasher $hasher;

    private Extractor $extractor;

    private Replacer $replacer;

    private Saver $saver;

    public function __construct()
    {
        $this->styleMap = new DisciteMap();

        $this->hasher = new Hasher();
        
        $this->extractor = new Extractor();
        
        $this->replacer = new Replacer();

        $this->saver = new Saver();
    }

    /**
     * Get the style map.
     *
     * @return DisciteMap The style map.
     */
    public function maps(): DisciteMap
    {
        return $this->styleMap;
    }

    /**
     * Get the saver instance.
     *
     * @return Saver The saver instance.
     */
    public function saver(): Saver
    {
        return $this->saver;
    }

    /**
     * Obfuscate the given CSS or JS code.
     *
     * @param string $css The CSS or JS code to obfuscate.
     * @param string $type The type of code ('css' or 'js').
     *
     * @return string The obfuscated CSS or JS code.
     */
    public function obfuscate(string $code, string $type = 'css'): string
    {
        if($type === 'css')
        {
            $classes = $this->extractor->extractClassesFromCSS($code);
            $ids     = $this->extractor->extractIdsFromCSS($code);
            $vars    = $this->extractor->extractVarsFromCSS($code);
            $attr    = $this->extractor->extractAttributesFromCSS($code);
        }
        elseif($type === 'js')
        {
            $classes = $this->extractor->extractClassesFromJS($code);
            $ids     = $this->extractor->extractIdsFromJS($code);
            $vars    = $this->extractor->extractVarsFromJS($code);
            $attr    = $this->extractor->extractAttributesFromJS($code);
        }
        else
        {
            throw new \InvalidArgumentException("Unsupported code format for obfuscation.");
        }

        // sorts by length descending to avoid partial replacements
        uksort($classes, fn($a,$b) => strlen($b) <=> strlen($a));
        uksort($ids, fn($a,$b) => strlen($b) <=> strlen($a));
        uksort($vars, fn($a,$b) => strlen($b) <=> strlen($a));
        uksort($attr, fn($a,$b) => strlen($b) <=> strlen($a));


        // Build maps
        foreach ($classes as $c) {
            $obf = $this->hasher->formatClass();
            $this->styleMap->addToClassesMap($c, $obf);
        }

        foreach ($ids as $i) {
            $obf = $this->hasher->formatId();
            $this->styleMap->addToIdsMap($i, $obf);
        }

        foreach ($vars as $v) {
            $obf = $this->hasher->formatVar();
            $this->styleMap->addToVarsMap($v, $obf);
        }

        foreach ($attr as $a) {
            $obf = $this->hasher->formatAttribute();
            $this->styleMap->addToAttributesMap($a, $obf);
        }

        // Replace
        if($type === 'css')
        {
            $code = $this->replacer->cssClassesReplace($code, $this->styleMap->classes());
            $code = $this->replacer->cssIdsReplace($code, $this->styleMap->ids());
            $code = $this->replacer->cssVarsReplace($code, $this->styleMap->vars());
            $code = $this->replacer->cssAttributesReplace($code, $this->styleMap->attributes());
        }
        elseif($type === 'js')
        {
            $code = $this->replacer->jsClassesReplace($code, $this->styleMap->classes());
            $code = $this->replacer->jsIdsReplace($code, $this->styleMap->ids());
            $code = $this->replacer->jsVarsReplace($code, $this->styleMap->vars());
            $code = $this->replacer->jsAttributesReplace($code, $this->styleMap->attributes());
        }

        return $code;
    }


    /**
     * Create INI map files.
     *
     * @param string $directory The directory to save the INI files.
     * @param string $filenameBase The base name for the INI files.
     * @return void
     */
    public function createMapIniFiles(string $directory, string $filenameBase): void
    {
        $this->saver->mapToIniFile($this->styleMap->all(), $directory, $filenameBase);
    }

    
    /**
     * Create JSON map files.
     *
     * @param string $directory The directory to save the JSON files.
     * @param string $filenameBase The base name for the JSON files.
     * @return void
     */
    public function createMapJsonFiles(string $directory, string $filenameBase): void
    {
        $this->saver->mapToJsonFile($this->styleMap->all(), $directory, $filenameBase);
    }

    /**
     * Load map from JSON file.
     *
     * @param string $filepath The path to the JSON file.
     * @return void
     */
    public function loadMapFromJsonFile(string $filepath): void
    {
        $map = $this->saver->mapFromJsonFile($filepath);

        if (isset($map['classes'])) {
            $this->styleMap->setClassesMap($map['classes']);
        }

        if (isset($map['ids'])) {
            $this->styleMap->setIdsMap($map['ids']);
        }

        if (isset($map['vars'])) {
            $this->styleMap->setVarsMap($map['vars']);
        }
    }

    
    /**
     * Load map from INI file.
     *
     * @param string $filepath The path to the INI file.
     * @return void
     */
    public function loadMapFromIniFile(string $filepath): void
    {
        $map = $this->saver->mapFromIniFile($filepath);

        if (isset($map['classes'])) {
            $this->styleMap->setClassesMap($map['classes']);
        }

        if (isset($map['ids'])) {
            $this->styleMap->setIdsMap($map['ids']);
        }

        if (isset($map['vars'])) {
            $this->styleMap->setVarsMap($map['vars']);
        }
    }


    /**
     * Save obfuscated CSS to a file.
     *
     * @param string $css The obfuscated CSS code.
     * @param string $filepath The path to the output file.
     * @return void
     */
    public function saveObfuscatedCssToFile(string $css, string $filepath): void
    {
        $this->saver->cssToFile($css, $filepath);
    }
}

?>