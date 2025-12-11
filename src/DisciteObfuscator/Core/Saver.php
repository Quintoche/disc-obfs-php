<?php

namespace DisciteObfuscator\Core;

class Saver
{
    /** 
     * Save CSS content to a file
     *
     * @param string $css The CSS content to save
     * @param string $filepath The path to the file where the CSS should be saved
     * @return void
     */
    public static function cssToFile(string $css, string $filepath) : void
    {
        file_put_contents($filepath, $css);
    }


    /**
     * Save a mapping array to a JSON file
     *
     * @param array $map The mapping array to save
     * @param string $filepath The path to the directory where the JSON file should be saved
     * @param string $filename The name of the JSON file (without extension)
     * @return void
     */
    public static function mapToJsonFile(array $map, string $filepath, string $filename) : void
    {
        $jsonData = self::formatJsonString($map);
        file_put_contents("$filepath/$filename.json", $jsonData);
    }

    
    /**
     * Load a mapping array from a JSON file
     *
     * @param string $filepath The path to the JSON file
     * @return array The mapping array
     */
    public static function mapFromJsonFile(string $filepath): array
    {
        $jsonData = file_get_contents($filepath);
        return self::parseJsonString($jsonData);
    }


    /**
     * Save a mapping array to an INI file
     *
     * @param array $map The mapping array to save
     * @param string $filePath The path to the directory where the INI file should be saved
     * @param string $fileName The name of the INI file (without extension)
     * @return void
     */
    public static function mapToIniFile(array $map, string $filePath, string $fileName) : void
    {
        $iniData = self::formatIniString($map);
        file_put_contents("$filePath/$fileName.ini", $iniData);
    }


    /**
     * Load a mapping array from an INI file
     *
     * @param string $filePath The path to the INI file
     * @return array The mapping array
     */
    public static function mapFromIniFile(string $filePath) : array
    {
        $iniData = file_get_contents($filePath);
        return self::parseIniString($iniData);
    }

    
    /**
     * Format an array as an INI string with sections
     *
     * @param array $map The mapping array
     * @return string The formatted INI string
     */
    private static function formatIniString(array $map) : string
    {
        $iniData = '';
        foreach ($map as $section => $values) {
            if (is_array($values)) {
                $iniData .= "[$section]\n";
                foreach ($values as $key => $value) {
                    $iniData .= "$key = $value\n";
                }
            } else {
                $iniData .= "$section = $values\n";
            }
        }
        return $iniData;
    }

    /**
     * Parse an INI string into an array with sections
     *
     * @param string $iniString The INI string
     * @return array The parsed mapping array
     */
    private static function parseIniString(string $iniString) : array
    {
        $iniData = [];
        $lines = explode("\n", $iniString);
        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === ';' || $line[0] === '#') {
                continue;
            }

            if ($line[0] === '[' && substr($line, -1) === ']') {
                $currentSection = substr($line, 1, -1);
                $iniData[$currentSection] = [];
            } elseif (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                if ($currentSection) {
                    $iniData[$currentSection][$key] = $value;
                } else {
                    $iniData[$key] = $value;
                }
            }
        }

        return $iniData;
    }
    

    /**
     * Format an array as a JSON string
     *
     * @param array $map The mapping array
     * @return string The formatted JSON string
     */
    private static function formatJsonString(array $map) : string
    {
        return json_encode($map, JSON_PRETTY_PRINT);
    }


    /**
     * Parse a JSON string into an array
     *
     * @param string $jsonString The JSON string
     * @return array The parsed mapping array
     */
    private static function parseJsonString(string $jsonString) : array
    {
        return json_decode($jsonString, true);
    }



    
    

}