<?php

namespace DisciteObfuscator;

class DisciteMap
{
    /** 
     * Map of obfuscated styles
     *
     * @var array
     */
    private array $classesMap = [];

    /**
     * Map of obfuscated IDs
     *
     * @var array
     */
    private array $idsMap = [];

    /**
     * Map of obfuscated variables
     *
     * @var array
     */
    private array $varsMap = [];

    /**
     * Map of obfuscated attributes
     *
     * @var array
     */
    private array $attributesMap = [];

    /**
     * Get the complete style map
     *
     * @return array
     */
    public function all(): array
    {
        return [
            'classes' => $this->classesMap,
            'ids' => $this->idsMap,
            'vars' => $this->varsMap,
            'attributes' => $this->attributesMap
        ];
    }

    /**
     * Classes map getter
     * 
     * @return array<string, string>
     */
    public function classes() : array
    {
        return $this->classesMap;
    }

    /**
     * IDs map getter
     *
     * @return array<string, string>
     */
    public function ids() : array
    {
        return $this->idsMap;
    }

    /**
     * Vars map getter
     *
     * @return array<string, string>
     */
    public function vars() : array
    {
        return $this->varsMap;
    }

    /**
     * Attributes map getter
     *
     * @return array<string, string>
     */
    public function attributes() : array
    {
        return $this->attributesMap;
    }

    /**
     * Set the classes map
     *
     * @param array<string, string> $map
     */
    public function setClassesMap(array $map): void
    {
        $this->classesMap = $map;
    }

    /**
     * Set the IDs map
     *
     * @param array<string, string> $map
     */
    public function setIdsMap(array $map): void
    {
        $this->idsMap = $map;
    }

    /**
     * Set the vars map
     *
     * @param array<string, string> $map
     */
    public function setVarsMap(array $map): void
    {
        $this->varsMap = $map;
    }

    /**
     * Set the attributes map
     *
     * @param array<string, string> $map
     */
    public function setAttributesMap(array $map): void
    {
        $this->attributesMap = $map;
    }

    /**
     * Add a single mapping to the classes, ids, vars, or attributes map
     *
     * @param string $original The original style name
     * @param string $obfuscated The obfuscated style name
     * @return void
     */
    public function addToClassesMap(string $original, string $obfuscated): void
    {
        $this->classesMap[$original] = $obfuscated;
    }

    /**
     * Add a single mapping to the IDs map
     *
     * @param string $original The original ID name
     * @param string $obfuscated The obfuscated ID name
     * @return void
     */
    public function addToIdsMap(string $original, string $obfuscated): void
    {
        $this->idsMap[$original] = $obfuscated;
    }

    /**
     * Add a single mapping to the vars map
     *
     * @param string $original The original variable name
     * @param string $obfuscated The obfuscated variable name
     * @return void
     */
    public function addToVarsMap(string $original, string $obfuscated): void
    {
        $this->varsMap[$original] = $obfuscated;
    }

    /**
     * Add a single mapping to the attributes map
     *
     * @param string $original The original attribute name
     * @param string $obfuscated The obfuscated attribute name
     * @return void
     */
    public function addToAttributesMap(string $original, string $obfuscated): void
    {
        $this->attributesMap[$original] = $obfuscated;
    }
}

?>