<?php

namespace DisciteObfuscator\Core;

use DisciteObfuscator\Config\AttributesConfig;
use DisciteObfuscator\Config\ClassesConfig;
use DisciteObfuscator\Config\IdsConfig;
use DisciteObfuscator\Config\VarsConfig;

class Extractor
{
    /**
     * Extract HTML IDs from the given CSS string.
     *
     * @param string $css The CSS string to extract IDs from
     *
     * @return array List of extracted HTML IDs
     */
    public static function extractIdsFromCSS(string $css) : array
    {
        preg_match_all('/#([a-zA-Z0-9_\p{Pd}]+)/u', $css, $matches);
        $ids = array_unique($matches[1]);

        $ids = array_filter($ids, function($id) {
            return !preg_match('/^[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/', $id);
        });

        return self::filterExclusions($ids, 'id');
    }

    /**
     * Extract HTML IDs from the given JavaScript string.
     *
     * @param string $js The JavaScript string to extract IDs from
     *
     * @return array List of extracted HTML IDs
     */
    public static function extractIdsFromJS(string $js) : array
    {
        $ids = [];

        // getElementById("xxx")
        preg_match_all('/getElementById\(\s*[\'"]([a-zA-Z_][a-zA-Z0-9_-]*)[\'"]\s*\)/', $js, $m);
        $ids = array_merge($ids, $m[1]);

        // querySelector("#xxx")
        preg_match_all('/querySelector(?:All)?\(\s*[\'"]#([a-zA-Z_][a-zA-Z0-9_-]*)[\'"]\s*\)/', $js, $m);
        $ids = array_merge($ids, $m[1]);

        // Jquery style $('#xxx')
        preg_match_all('/#([a-zA-Z_][a-zA-Z0-9_-]*)\\b/', $js, $m);
        $ids = array_merge($ids, $m[1]);

        $ids = array_unique($ids);
        return self::filterExclusions($ids, 'id');
    }

    /**
     * Extract CSS classes from the given CSS string.
     *
     * @param string $css The CSS string to extract classes from
     *
     * @return array List of extracted CSS classes
     */
    public static function extractClassesFromCSS(string $css) : array
    {
        preg_match_all('/\.(?!\d)([a-zA-Z0-9_\p{Pd}]+)/u', $css, $matches);
        $classes = array_unique($matches[1]);

        return self::filterExclusions($classes, 'class');
    }

    /**
     * Extract CSS classes from the given JavaScript string.
     *
     * @param string $js The JavaScript string to extract classes from
     *
     * @return array List of extracted CSS classes
     */
    public static function extractClassesFromJS(string $js) : array
    { 
        $classes = [];

        // classList.add/remove/toggle("xxx")
        preg_match_all('/classList\.(?:add|remove|toggle|contains)\(\s*[\'"]([a-zA-Z_][a-zA-Z0-9_-]*)[\'"]\s*\)/', $js, $m);
        $classes = array_merge($classes, $m[1]);

        // element.className = "xxx"
        preg_match_all('/className\s*=\s*[\'"]([a-zA-Z_][a-zA-Z0-9_-]*)[\'"]/', $js, $m);
        $classes = array_merge($classes, $m[1]);

        // getElementsByClassName("xxx")
        preg_match_all('/getElementsByClassName\(\s*[\'"]([a-zA-Z_][a-zA-Z0-9_-]*)[\'"]\s*\)/', $js, $m);
        $classes = array_merge($classes, $m[1]);

        // querySelector(".xxx")
        preg_match_all('/querySelector(?:All)?\(\s*[\'"]\.([a-zA-Z_][a-zA-Z0-9_-]*)[\'"]\s*\)/', $js, $m);
        $classes = array_merge($classes, $m[1]);

        //Jquery style $('.xxx')
        preg_match_all('/\.([a-zA-Z_][a-zA-Z0-9_-]*)\\b/', $js, $m);
        $classes = array_merge($classes, $m[1]);

        $classes = array_unique($classes);
        return self::filterExclusions($classes, 'class');
    }

    /**
     * Extract CSS variables from the given CSS string.
     *
     * @param string $css The CSS string to extract variables from
     *
     * @return array List of extracted CSS variables
     */
    public static function extractVarsFromCSS(string $css) : array
    {
        preg_match_all('/--[a-zA-Z0-9_-]+(?=\s*:|(?<!var\()\s*\))/u', $css, $m);
        $vars = array_unique($m[0]);

        return self::filterExclusions($vars, 'var');
    }

    /**
     * Extract CSS variables from the given JavaScript string.
     *
     * @param string $js The JavaScript string to extract variables from
     *
     * @return array List of extracted CSS variables
     */
    public static function extractVarsFromJS(string $js) : array
    {
        $vars = [];

        // setProperty("--xxx", ...)
        preg_match_all('/setProperty\(\s*[\'"](--[a-zA-Z0-9_-]+)[\'"]\s*,/u', $js, $m);
        $vars = array_merge($vars, $m[1]);

        // getPropertyValue("--xxx")
        preg_match_all('/getPropertyValue\(\s*[\'"](--[a-zA-Z0-9_-]+)[\'"]\s*\)/u', $js, $m);
        $vars = array_merge($vars, $m[1]);

        // Jquery style var(--xxx)
        preg_match_all('/var\(\s*(--[a-zA-Z0-9_-]+)\s*\)/u', $js, $m);
        $vars = array_merge($vars, $m[1]);

        $vars = array_unique($vars);
        return self::filterExclusions($vars, 'var');
    }

    /**
     * Extract CSS attributes from the given CSS string.
     *
     * @param string $css The CSS string to extract attributes from
     *
     * @return array List of extracted CSS attributes
     */
    public static function extractAttributesFromCSS(string $css) : array
    {
        preg_match_all('/\[\s*([a-zA-Z_][a-zA-Z0-9_-]*)\s*(?:[~|^$*]?=\s*[\'"][^\'"]*[\'"])?\s*\]/', $css, $matches);
        $attributes = array_unique($matches[1]);

        return self::filterExclusions($attributes, 'attr');
    }

    /**
     * Extract CSS attributes from the given JavaScript string.
     *
     * @param string $js The JavaScript string to extract attributes from
     *
     * @return array List of extracted CSS attributes
     */
    public static function extractAttributesFromJS(string $js) : array
    {
        $attrs = [];

        // getAttribute("xxx")
        preg_match_all('/getAttribute\(\s*[\'"]([a-zA-Z_][a-zA-Z0-9_-]*)[\'"]\s*\)/', $js, $m);
        $attrs = array_merge($attrs, $m[1]);

        // setAttribute("xxx", ...)
        preg_match_all('/setAttribute\(\s*[\'"]([a-zA-Z_][a-zA-Z0-9_-]*)[\'"]\s*,/u', $js, $m);
        $attrs = array_merge($attrs, $m[1]);

        // hasAttribute("xxx")
        preg_match_all('/hasAttribute\(\s*[\'"]([a-zA-Z_][a-zA-Z0-9_-]*)[\'"]\s*\)/u', $js, $m);
        $attrs = array_merge($attrs, $m[1]);

        // closest("xxx")
        preg_match_all('/closest\(\s*[\'"]\[([a-zA-Z_][a-zA-Z0-9_-]*)\s*(?:[~|^$*]?=\s*[\'"][^\'"]*[\'"])?\][\'"]\s*\)/', $js, $m);
        $attrs = array_merge($attrs, $m[1]);

        // dataset.xxx
        preg_match_all('/dataset\.([a-zA-Z_][a-zA-Z0-9_-]*)\b/', $js, $m);
        $attrs = array_merge($attrs, $m[1]);

        // WIP :
        // need to improve dataset.xxx when xxx contains others chars like hyphen (-) that are not valid in JS variable names
        // and automatically convert into dataset.xxx.yyy

        // querySelector("[xxx]")
        preg_match_all('/querySelector(?:All)?\(\s*[\'"]\[([a-zA-Z_][a-zA-Z0-9_-]*)\s*(?:[~|^$*]?=\s*[\'"][^\'"]*[\'"])?\][\'"]\s*\)/', $js, $m);
        $attrs = array_merge($attrs, $m[1]);

        // jQuery style $("[xxx]")
        preg_match_all('/\$\(\s*[\'"]\[([a-zA-Z_][a-zA-Z0-9_-]*)\][\'"]\s*\)/', $js, $m);
        $attrs = array_merge($attrs, $m[1]);

        $attrs = array_unique($attrs);
        return self::filterExclusions($attrs, 'attr');
    }

    /**
     * Filter out excluded items based on type.
     * 
     * @param array $items List of items to filter
     * @param string $type 'id', 'class', or 'var'
     * 
     * @return array Filtered list of items
     */
    private static function filterExclusions(array $items, string $type) : array
    {
        $exclusions = self::retrieveExclusions($type);
        return array_values(array_filter($items, function ($item) use ($exclusions) {
            return !in_array($item, $exclusions);
        }));
    }

    /**
     * Retrieve exclusion list based on the type.
     * 
     * @param string $type 'id', 'class', or 'var'
     * 
     * @return array List of exclusions
     */
    private static function retrieveExclusions(string $type) : array
    {
        return match(true)
        {
            $type === 'id' => IdsConfig::$excludedIds,
            $type === 'class' => ClassesConfig::$excludedClasses,
            $type === 'var' => VarsConfig::$excludedVariables,
            $type === 'attr' => AttributesConfig::$excludedAttributes,
            default => [],
        };
    }
}

?>