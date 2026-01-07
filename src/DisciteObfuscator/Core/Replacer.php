<?php

namespace DisciteObfuscator\Core;

class Replacer
{

    /**
     * Replace CSS classes based on the provided map.
     *
     * @param string $css The CSS code.
     * @param array $map The mapping of original to obfuscated class names.
     * @return string The CSS code with classes replaced.
     */
    public function cssClassesReplace(string $css, array $map): string
    {
        if (empty($map)) return $css;

        uksort($map, fn($a,$b) => strlen($b) <=> strlen($a));

        foreach ($map as $from => $to)
        {
            $from = preg_quote($from, '/');
            $css = preg_replace('/(?<=\.)' . $from . '(?![a-zA-Z0-9_-])/u', $to, $css);
        }

        return $css;
    }

    /**
     * Replace CSS IDs based on the provided map.
     *
     * @param string $css The CSS code.
     * @param array $map The mapping of original to obfuscated ID names.
     * @return string The CSS code with IDs replaced.
     */
    public function cssIdsReplace(string $css, array $map): string
    {
        if (empty($map)) return $css;

        return preg_replace_callback(
            '/#([a-zA-Z0-9_-]+)\b/u',
            function ($matches) use ($map) {
                $id = $matches[1];

                // Skip hex colors (#fff, #ffffff)
                if (preg_match('/^[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/', $id)) {
                    return '#' . $id;
                }

                // Replace if found in map
                if (isset($map[$id])) {
                    return '#' . $map[$id];
                }

                return '#' . $id;
            },
            $css
        );
    }

    /**
     * Replace CSS variables based on the provided map.
     *
     * @param string $css The CSS code.
     * @param array $map The mapping of original to obfuscated variable names.
     * @return string The CSS code with variables replaced.
     */
    public function cssVarsReplace(string $css, array $map): string
    {
        if (empty($map)) return $css;

        uksort($map, fn($a,$b) => strlen($b) <=> strlen($a));

        foreach ($map as $from => $to)
        {
            $from = preg_quote($from, '/');
            $css = preg_replace('/' . $from . '(?=\s*[:),])/u', $to, $css);
        }

        return $css;
    }

    /**
     * Replace CSS attributes based on the provided map.
     *
     * @param string $css The CSS code.
     * @param array $map The mapping of original to obfuscated attribute names.
     * @return string The CSS code with attributes replaced.
     */
    public function cssAttributesReplace(string $css, array $map): string
    {
        if (empty($map)) return $css;

        uksort($map, fn($a,$b) => strlen($b) <=> strlen($a));

        foreach ($map as $from => $to)
        {
            $from = preg_quote($from, '/');
            $css = preg_replace('/\[' . $from . '(?=[~|^$*]?=|\])/u', '[' . $to, $css);
        }

        return $css;
    }

    /**
     * Replace JavaScript classes based on the provided map.
     *
     * @param string $js The JavaScript code.
     * @param array $map The mapping of original to obfuscated class names.
     * @return string The JavaScript code with classes replaced.
     */
    public function jsClassesReplace(string $js, array $map): string
    {
        if (empty($map)) return $js;

        uksort($map, fn($a,$b) => strlen($b) <=> strlen($a));

        foreach ($map as $from => $to) {

            $fromQ = preg_quote($from, '/');

            // classList.add/remove/toggle("class")
            $js = preg_replace(
                '/classList\.(add|remove|toggle)\(\s*[\'"]' . $fromQ . '[\'"]\s*\)/u',
                'classList.$1("'.$to.'")',
                $js
            );

            // element.className = "class"
            $js = preg_replace(
                '/className\s*=\s*[\'"]' . $fromQ . '[\'"]/u',
                'className="'.$to.'"',
                $js
            );

            // getElementsByClassName("class")
            $js = preg_replace(
                '/getElementsByClassName\(\s*[\'"]' . $fromQ . '[\'"]\s*\)/u',
                'getElementsByClassName("'.$to.'")',
                $js
            );

            // querySelector(".class")
            $js = preg_replace(
                '/querySelector\(\s*[\'"]\.' . $fromQ . '[\'"]\s*\)/u',
                'querySelector(".'.$to.'")',
                $js
            );

            // querySelectorAll(".class")
            $js = preg_replace(
                '/querySelectorAll\(\s*[\'"]\.' . $fromQ . '[\'"]\s*\)/u',
                'querySelectorAll(".'.$to.'")',
                $js
            );
        }

        return $js;
    }

    /**
     * Replace JavaScript IDs based on the provided map.
     *
     * @param string $js The JavaScript code.
     * @param array $map The mapping of original to obfuscated ID names.
     * @return string The JavaScript code with IDs replaced.
     */
    public function jsIdsReplace(string $js, array $map): string
    {
        if (empty($map)) return $js;

        uksort($map, fn($a,$b) => strlen($b) <=> strlen($a));

        foreach ($map as $from => $to) {

            $fromQ = preg_quote($from, '/');

            // getElementById("id")
            $js = preg_replace(
                '/getElementById\(\s*[\'"]' . $fromQ . '[\'"]\s*\)/u',
                'getElementById("'.$to.'")',
                $js
            );

            // querySelector("#id")
            $js = preg_replace(
                '/querySelector\(\s*[\'"]#' . $fromQ . '[\'"]\s*\)/u',
                'querySelector("#'.$to.'")',
                $js
            );

            // querySelectorAll("#id")
            $js = preg_replace(
                '/querySelectorAll\(\s*[\'"]#' . $fromQ . '[\'"]\s*\)/u',
                'querySelectorAll("#'.$to.'")',
                $js
            );
        }

        return $js;
    }

    /**
     * Replace JavaScript variables based on the provided map.
     *
     * @param string $js The JavaScript code.
     * @param array $map The mapping of original to obfuscated variable names.
     * @return string The JavaScript code with variables replaced.
     */
    public function jsVarsReplace(string $js, array $map): string
    {
        if (empty($map)) return $js;

        uksort($map, fn($a,$b) => strlen($b) <=> strlen($a));

        foreach ($map as $from => $to) {

            $fromQ = preg_quote($from, '/');

            // setProperty("--test-var", ...)
            $js = preg_replace(
                '/setProperty\(\s*[\'"]' . $fromQ . '[\'"]\s*,/u',
                'setProperty("'.$to.'",',
                $js
            );

            // getPropertyValue("--test-var")
            $js = preg_replace(
                '/getPropertyValue\(\s*[\'"]' . $fromQ . '[\'"]\s*\)/u',
                'getPropertyValue("'.$to.'")',
                $js
            );
        }

        return $js;
    }

    /**
     * Replace JavaScript attributes based on the provided map.
     *
     * @param string $js The JavaScript code.
     * @param array $map The mapping of original to obfuscated attribute names.
     * @return string The JavaScript code with attributes replaced.
     */
    public function jsAttributesReplace(string $js, array $map): string
    {
        if (empty($map)) return $js;

        uksort($map, fn($a,$b) => strlen($b) <=> strlen($a));

        foreach ($map as $from => $to) {

            $fromQ = preg_quote($from, '/');

            // getAttribute("data-x")
            $js = preg_replace(
                '/getAttribute\(\s*[\'"]' . $fromQ . '[\'"]\s*\)/u',
                'getAttribute("'.$to.'")',
                $js
            );

            // setAttribute("data-x", ...)
            $js = preg_replace(
                '/setAttribute\(\s*[\'"]' . $fromQ . '[\'"]\s*,/u',
                'setAttribute("'.$to.'",',
                $js
            );

            // dataset.x
            $js = preg_replace(
                '/dataset\.' . $fromQ . '\b/u',
                'dataset.'.$to,
                $js
            );

            // querySelector("[attr]")
            $js = preg_replace(
                '/querySelector\(\s*[\'"]\[' . $fromQ . '\][\'"]\s*\)/u',
                'querySelector("['.$to.']")',
                $js
            );
        }

        return $js;
    }
}

?>