<?php

ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);

require_once __DIR__ . '../../vendor/autoload.php';

function testExecutionTime(callable $callback): float
{
    $start = microtime(true); // Start time in seconds with microseconds
    $callback();              // Run the code
    $end = microtime(true);   // End time
    return $end - $start;     // Duration in seconds
}


$duration = testExecutionTime(function()
{
    // Put your code to test here
    $obfs = new \DisciteObfuscator\DisciteObfuscator();
    $css = file_get_contents(__DIR__ . '/defaultStyle.css');
    
    $obfuscatedCSS = $obfs->obfuscate($css, 'css');
    
    $obfs->saver()->cssToFile($obfuscatedCSS, __DIR__ . '/defaultStyle.obfuscated.css');
    $obfs->saver()->mapToJsonFile($obfs->maps()->all(), __DIR__, 'maps.defaultStyle');
});

echo "Execution time: {$duration} seconds\n";



?>