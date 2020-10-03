<?php
require __DIR__ . "/../../vendor/autoload.php";

use \Doctrine\Common\Annotations\AnnotationReader;
use \Doctrine\Common\Annotations\AnnotationRegistry;

$path = __DIR__ . "/../../src/classes/Controllers/";
$fqcns = array();

$allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$phpFiles = new RegexIterator($allFiles, '/\.php$/');
foreach ($phpFiles as $phpFile) {
    $content = file_get_contents($phpFile->getRealPath());
    $tokens = token_get_all($content);
    $namespace = '';
    for ($index = 0; isset($tokens[$index]); $index++) {
        if (!isset($tokens[$index][0])) {
            continue;
        }
        if (T_NAMESPACE === $tokens[$index][0]) {
            $index += 2; // Skip namespace keyword and whitespace
            while (isset($tokens[$index]) && is_array($tokens[$index])) {
                $namespace .= $tokens[$index++][1];
            }
        }
        if (T_CLASS === $tokens[$index][0]) {
            $index += 2; // Skip class keyword and whitespace
            $fqcns[] = $namespace.'\\'.$tokens[$index][1];
        }
    }
}

AnnotationRegistry::registerLoader('class_exists');
$reader = new AnnotationReader();

$routeNames = [];
$classString = '<?php

namespace dhope0000\LXDClient\Objects;

class RouteToNameMapping
{
    public $routesToName = REPLACE_ME;

    public function getControllerName(string $controller)
    {
        if (!isset($this->routesToName[$controller])) {
            return "";
        }
        return $this->routesToName[$controller];
    }
}';

foreach ($fqcns as $class) {
    $refClass = new ReflectionClass($class);
    if (!$refClass->implementsInterface("dhope0000\LXDClient\Interfaces\RecordAction")) {
        continue;
    }
    $methods = $refClass->getMethods(ReflectionMethod::IS_PUBLIC);
    foreach ($methods as $method) {
        if ($method->getName() == "__construct") {
            continue;
        }

        $route = $reader->getMethodAnnotation(
            new ReflectionMethod($class, $method->getName()),
            Symfony\Component\Routing\Annotation\Route::class
        );

        if ($route !== null) {
            $routeNames[$class . "\\" . $method->getName()] = $route->getName();
        } else {
            echo "Missing " . $class . "::" . $method->getName() . PHP_EOL;
        }
    }
}

// Old routes we just manually add because they wont be found by the script
// anymore
$routeNames['dhope0000\\LXDClient\\Controllers\\Images\\ImportLinuxContainersByAliasController\\import'] = 'Import LinunxContainer.Org Image';


$routesString = var_export($routeNames, true);
$classString = str_replace("REPLACE_ME", $routesString, $classString);
file_put_contents(__DIR__ . "/../classes/Objects/RouteToNameMapping.php", $classString);
